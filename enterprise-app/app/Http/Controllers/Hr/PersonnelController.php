<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PersonnelController extends Controller
{
    // แสดงรายชื่อทั้งหมด
    public function index()
    {
        $employees = Employee::with(['branch', 'position', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['status' => 'success', 'data' => $employees]);
    }

    // เพิ่มพนักงานใหม่
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // 1. สร้าง User
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make('12345678'),
            ]);

            // 2. สร้าง Employee
            $employee = Employee::create([
                'user_id' => $user->id,
                'prefix' => $request->prefix,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone_number' => $request->phone_number,
                'id_card_number' => $request->id_card_number,
                'branch_id' => $request->branch_id,
                'position_id' => $request->position_id,
                'status' => 'active',
            ]);

            DB::commit();
            return response()->json(['message' => 'Created successfully', 'data' => $employee], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    // แก้ไขข้อมูล (Update)
    public function update(Request $request, string $id)
    {
        $employee = Employee::findOrFail($id);

        // อัปเดตข้อมูลในตาราง Employee
        $employee->update([
            'prefix' => $request->prefix,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'id_card_number' => $request->id_card_number,
            'branch_id' => $request->branch_id,
            'position_id' => $request->position_id,
        ]);

        // อัปเดตชื่อในตาราง User ด้วย
        if ($employee->user) {
            $employee->user->update([
                'name' => $request->first_name . ' ' . $request->last_name
            ]);
        }

        return response()->json(['message' => 'Updated successfully']);
    }

    // ลบข้อมูล (Delete)
    public function destroy(string $id)
    {
        $employee = Employee::findOrFail($id);

        // ลบ User ที่ผูกกันด้วย (Optional: ถ้าต้องการลบ Account ด้วย)
        if ($employee->user) {
            $employee->user->delete();
        }

        $employee->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
