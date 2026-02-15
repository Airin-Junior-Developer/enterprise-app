<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\Request as RequestModel;
use App\Models\ViewRequest;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->isAdminOrHr()) {
            $requests = ViewRequest::orderBy('created_at', 'desc')->get();
        } else {
            $requests = ViewRequest::where('user_id', $user->user_id)
                ->orderBy('created_at', 'desc')->get();
        }
        return response()->json($requests);
    }

    // ✅ เพิ่มฟังก์ชัน getTypes สำหรับ Dropdown (ถ้าไม่มี Dropdown จะว่าง)
    public function getTypes()
    {
        $types = DB::table('requests_type')
            ->where('is_active', 1)
            ->select('id', 'Name_Type')
            ->get();
        return response()->json($types);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'request_type_id' => 'required|exists:requests_type,id',
            'subject' => 'required|string|max:255',
            'reason' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'amount' => 'nullable|numeric',
        ]);

        $validated['status'] = 'Pending';
        RequestModel::create($validated);

        return response()->json(['message' => 'สร้างคำร้องสำเร็จ']);
    }

    // ✅ เพิ่มฟังก์ชันนี้เพื่อรับ Route /status (แก้ Error 404)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Approved,Rejected'
        ]);

        try {
            $req = RequestModel::findOrFail($id);
            $req->status = $request->status;
            $req->approver_id = auth()->id();
            $req->updated_at = now();
            $req->save();

            return response()->json(['message' => 'ดำเนินการเรียบร้อยแล้ว']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Approved,Rejected,Cancelled',
            'comment' => 'nullable|string'
        ]);

        $req = RequestModel::findOrFail($id);
        $req->status = $request->status;
        $req->comment = $request->comment;
        $req->approver_id = auth()->id();
        $req->save();

        return response()->json(['message' => 'Status updated']);
    }

    public function destroy($id)
    {
        RequestModel::destroy($id);
        return response()->json(['message' => 'ลบข้อมูลสำเร็จ']);
    }
}