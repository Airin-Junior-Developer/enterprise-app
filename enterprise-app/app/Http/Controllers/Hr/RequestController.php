<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\Request as RequestModel; // ตั้งชื่อเล่น Model เพื่อไม่ให้ชนกับ Request หลัก
use App\Models\User;
use App\Models\ViewRequest;


class RequestController extends Controller
{
    // ดึงข้อมูลคำร้อง (กรองตามสิทธิ์)
    public function index(Request $request)
    {
        $user = $request->user(); // ดึง User คนปัจจุบันที่ล็อกอินอยู่

        // 2. ถ้าเป็น Admin หรือ HR -> ให้ดูทั้งหมด (All)
        if ($user->isAdminOrHr()) {
            $requests = ViewRequest::orderBy('created_at', 'desc')->get();
        }
        // 3. ถ้าเป็นพนักงานทั่วไป -> ดูเฉพาะของตัวเอง (Where user_id)
        else {
            $requests = ViewRequest::where('user_id', $user->user_id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return response()->json($requests);
    }

    // สร้างคำร้องใหม่
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'request_type' => 'required|string',
            'reason' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'amount' => 'nullable|numeric',
        ]);

        // ตั้งสถานะเริ่มต้นเป็น pending (รออนุมัติ)
        $validated['status'] = 'pending';

        RequestModel::create($validated);

        return response()->json(['message' => 'สร้างคำร้องสำเร็จ']);
    }

    // RequestController.php

    public function update(Request $request, $id)
    {
        // ถูกต้อง: ใช้ Model จริงเพื่อแก้ไขข้อมูล
        $req = RequestModel::findOrFail($id);

        // ผิด: ViewRequest::findOrFail($id) (View แก้ไขไม่ได้)

        $req->status = $request->status;
        $req->save();

        return response()->json(['message' => 'Status updated']);
    }
    // ลบคำร้อง
    public function destroy($id)
    {
        RequestModel::destroy($id);
        return response()->json(['message' => 'ลบข้อมูลสำเร็จ']);
    }
}