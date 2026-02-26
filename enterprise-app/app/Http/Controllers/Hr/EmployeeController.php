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
        // ✅ 1. ปรับ Validation ให้ยืดหยุ่นขึ้นเพื่อ Debug 
        // หากติด error 'id not exists' แสดงว่า ID ที่ส่งมาไม่มีอยู่ในตารางนั้นจริงๆ
        $validated = $request->validate([
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
            // 2. เข้ารหัสรหัสผ่าน
            $validated['password'] = Hash::make($request->password);

            // 3. สร้าง User
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id . ',user_id',
            'branch_id' => 'required',
            'position_id' => 'required',
            'employment_type_id' => 'required',
            'employee_category_id' => 'required',
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
   
   // สำหรับเปลี่ยนตำแหน่ง (รองรับทั้งแบบถาวรและชั่วคราว)
    public function updatePosition(Request $request, $id)
    {
        $request->validate([
            'position_id' => 'required',
            'is_temporary' => 'boolean|nullable',
            'end_date' => 'required_if:is_temporary,true|date|after_or_equal:today' // ถ้าชั่วคราว ต้องใส่วันที่
        ]);

        $user = User::where('user_id', $id)->firstOrFail();

        //  SECURITY CHECK (ดักจับสิทธิ์ HR เดิม)
        $currentUser = auth()->user();
        $currentUser->load('position'); 
        $requestedPosition = DB::table('positions')->where('position_id', $request->position_id)->first();

        if ($currentUser->position && ($currentUser->position->level_code === 'MGR' || $currentUser->position->position_name === 'HR Manager')) {
            if ($requestedPosition && ($requestedPosition->level_code === 'CEO' || str_contains(strtolower($requestedPosition->position_name), 'admin'))) {
                return response()->json(['message' => 'ปฏิเสธการเข้าถึง: ไม่อนุญาตให้ HR แต่งตั้งผู้ดูแลระบบได้'], 403);
            }
        }

        try {
            // การมอบตำแหน่งชั่วคราว
            if ($request->is_temporary) {
                // ถ้าพนักงานยังไม่มี original_position_id ให้เก็บตำแหน่งปัจจุบันไว้ก่อน
                if (is_null($user->original_position_id)) {
                    $user->original_position_id = $user->position_id;
                }
                $user->temp_position_end_date = $request->end_date;
            } else {
                // ถ้าเลือกมอบหมาย "ถาวร" ให้ล้างค่าชั่วคราวทิ้งทั้งหมด
                $user->original_position_id = null;
                $user->temp_position_end_date = null;
            }

            // อัปเดตตำแหน่งใหม่
            $user->position_id = $request->position_id;
            $user->save();
            
            return response()->json(['message' => 'อัปเดตตำแหน่งสำเร็จ']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Update Error: ' . $e->getMessage()], 500);
        }
    }
}