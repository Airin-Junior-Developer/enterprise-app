<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Hr\Request as RequestModel;

class RequestController extends Controller
{
    private function formatRequests($requests)
    {
        return $requests->map(function ($req) {
            return [
                'request_id' => $req->request_id,
                'user_id' => $req->user_id,
                'requester_first_name' => optional($req->requester)->first_name,
                'requester_last_name' => optional($req->requester)->last_name,
                'requester_branch' => optional(optional($req->requester)->branch)->branch_name,
                'requester_position' => optional(optional($req->requester)->position)->position_name,
                'request_type_name' => optional($req->requestType)->Name_Type,
                'subject' => $req->subject,
                'reason' => $req->reason,
                'start_date' => $req->start_date ? $req->start_date->toDateString() : null,
                'end_date' => $req->end_date ? $req->end_date->toDateString() : null,
                'amount' => $req->amount,
                'status' => $req->status,
                'created_at' => $req->created_at,
            ];
        });
    }

    // 1. ดึงรายการคำร้อง (แยกตามโหมด: ของฉัน vs หน้าอนุมัติ)
    public function index(Request $request)
    {
        $user = $request->user();

        // 🟢 ตรวจสอบว่าส่ง mode=approval มาจากหน้า "พิจารณาอนุมัติ" หรือไม่
        if ($request->query('mode') === 'approval') {
            // ถ้าเป็น HR หรือ Super Admin ให้ดึงข้อมูล "ทั้งหมด" มาแสดง
            if ($user->isAdminOrHr()) {
                $requests = RequestModel::with(['requester.branch', 'requester.position', 'requestType'])
                    ->orderBy('created_at', 'desc')
                    ->get();
                return response()->json($this->formatRequests($requests));
            }
            // ถ้าไม่ใช่ HR/Admin แอบเข้ามา ให้ส่ง Array ว่างกลับไป (ป้องกันการแอบดู)
            return response()->json([]);
        }

        // 🔵 ถ้าเป็นการเข้าหน้า "รายการคำร้อง" ปกติ ให้ดึงเฉพาะของ User คนปัจจุบัน
        $requests = RequestModel::with(['requester.branch', 'requester.position', 'requestType'])
            ->where('user_id', $user->user_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($this->formatRequests($requests));
    }

    // 2. ดึงประเภทคำร้อง (สำหรับ Dropdown)
    public function getTypes()
    {
        // ดึงเฉพาะที่เปิดใช้งาน (is_active = 1)
        $types = DB::table('requests_type')
            ->where('is_active', 1)
            ->get();

        return response()->json($types);
    }

    // 3. สร้างคำร้องใหม่
    public function store(Request $request)
    {
        $validated = $request->validate([
            'request_type_id' => 'required',
            'subject' => 'required|string|max:255',
            'reason' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date', // วันจบต้องไม่ก่อนวันเริ่ม
            'amount' => 'nullable|numeric|max:30000' // ไม่เกิน 30,000 บาท
        ]);

        // เติมข้อมูลที่เหลือ
        $validated['user_id'] = $request->user()->user_id;
        $validated['status'] = 'Pending'; // เริ่มต้นเป็น "รออนุมัติ" เสมอ
        $validated['created_at'] = now();
        $validated['updated_at'] = now();

        RequestModel::create($validated);

        return response()->json(['message' => 'สร้างคำร้องสำเร็จ']);
    }

    // 4. ยกเลิก/ลบคำร้อง (เฉพาะสถานะ Pending)
    public function destroy(Request $request, $id)
    {
        $userId = $request->user()->user_id;

        // เช็คว่าเป็นของเจ้าตัวจริงไหม และสถานะยังรออนุมัติอยู่ไหม
        $req = RequestModel::where('request_id', $id)
            ->where('user_id', $userId)
            ->where('status', 'Pending')
            ->first();

        if (!$req) {
            return response()->json(['message' => 'ไม่สามารถลบได้ (เอกสารอาจถูกอนุมัติไปแล้ว หรือไม่ใช่ของคุณ)'], 403);
        }

        $req->delete();

        return response()->json(['message' => 'ลบคำร้องสำเร็จ']);
    }

    // 5. อนุมัติ/ไม่อนุมัติ (สำหรับหัวหน้า)
    public function updateStatus(Request $request, $id)
    {
        // ตรวจสอบค่าที่ส่งมาว่าต้องเป็น Approved หรือ Rejected เท่านั้น
        $request->validate([
            'status' => 'required|in:Approved,Rejected'
        ]);

        try {
            $req = RequestModel::where('request_id', $id)->firstOrFail();
            $req->status = $request->status;

            // ถ้าตาราง requests ของคุณมีคอลัมน์เก็บว่าใครเป็นคนอนุมัติ สามารถเปิดใช้บรรทัดล่างนี้ได้
            // $req->approver_id = $request->user()->user_id; 

            $req->updated_at = now();
            $req->save();

            return response()->json(['message' => 'ดำเนินการเรียบร้อยแล้ว']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()], 500);
        }
    }
}