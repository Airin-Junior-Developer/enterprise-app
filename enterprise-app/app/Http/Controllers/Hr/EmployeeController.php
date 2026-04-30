<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\ViewEmployee;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index()
    {
        try {
            // ดึงข้อมูลจาก View เพื่อโชว์ในตาราง
            return response()->json(ViewEmployee::all());
        } catch (\Exception $e) {
            return response()->json(['message' => 'Database Error: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $employee = ViewEmployee::where('user_id', $id)->firstOrFail();
            return response()->json($employee);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not Found'], 404);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'prefix' => 'nullable|string|max:50', // ✅ เพิ่มคำนำหน้า
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'branch_id' => 'required',
            'position_id' => 'required',
            'employment_type_id' => 'required',
            'employee_category_id' => 'required',
            'password' => 'required|string|min:6|confirmed',
            'id_card_number' => 'nullable|string',
            'phone_number' => 'nullable|string',
        ]);

        try {
            // เข้ารหัสรหัสผ่าน
            $validated['password'] = Hash::make($request->password);

            // สร้าง User
            User::create($validated);

            return response()->json(['message' => 'เพิ่มพนักงานสำเร็จ']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Save Error: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::where('user_id', $id)->firstOrFail();

        $validated = $request->validate([
            'prefix' => 'nullable|string|max:50',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id . ',user_id',
            'branch_id' => 'nullable',
            'position_id' => 'nullable',
            'employment_type_id' => 'nullable',
            'employee_category_id' => 'nullable',
            'password' => 'nullable|min:6|confirmed',
            'id_card_number' => 'nullable|string',
            'phone_number' => 'nullable|string',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        try {
            $user->update($validated);
            return response()->json(['message' => 'อัปเดตข้อมูลสำเร็จ']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Update Error: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::where('user_id', $id)->firstOrFail();
            $user->delete();
            return response()->json(['message' => 'ลบข้อมูลสำเร็จ']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Delete Error: ' . $e->getMessage()], 500);
        }
    }

    public function updatePosition(Request $request, $id)
    {
        $request->validate([
            'position_id' => 'required',
            'is_temporary' => 'boolean|nullable',
            'end_date' => 'required_if:is_temporary,true|date|after_or_equal:today'
        ]);

        $user = User::where('user_id', $id)->firstOrFail();

        $currentUser = auth()->user();
        $currentUser->load('position');
        $requestedPosition = DB::table('positions')->where('position_id', $request->position_id)->first();

        if ($currentUser->position && ($currentUser->position->level_code === 'MGR' || $currentUser->position->position_name === 'HR Manager')) {
            if ($requestedPosition && ($requestedPosition->level_code === 'CEO' || str_contains(strtolower($requestedPosition->position_name), 'admin'))) {
                return response()->json(['message' => 'ปฏิเสธการเข้าถึง: ไม่อนุญาตให้ HR แต่งตั้งผู้ดูแลระบบได้'], 403);
            }
        }

        try {
            if ($request->is_temporary) {
                if (is_null($user->original_position_id)) {
                    $user->original_position_id = $user->position_id;
                }
                $user->temp_position_end_date = $request->end_date;
            } else {
                $user->original_position_id = null;
                $user->temp_position_end_date = null;
            }

            $user->position_id = $request->position_id;

            // ให้ is_notify_expired มีค่าเป็น 1 เสมอเมื่อบันทึกการเปลี่ยนตำแหน่ง
            $user->is_notify_expired = 1;

            $user->save();

            return response()->json(['message' => 'อัปเดตตำแหน่งสำเร็จ']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Update Error: ' . $e->getMessage()], 500);
        }
    }
}