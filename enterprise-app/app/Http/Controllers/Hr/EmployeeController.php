<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class EmployeeController extends Controller
{
    // Query พนักงานครบทุก field รวมถึง id_card_number, phone_number ฯลฯ
    private function employeeQuery()
    {
        return DB::table('users as u')
            ->leftJoin('branches as b', 'u.branch_id', '=', 'b.branch_id')
            ->leftJoin('positions as p', 'u.position_id', '=', 'p.position_id')
            ->leftJoin('employment_types as et', 'u.employment_type_id', '=', 'et.id')
            ->leftJoin('employee_categories as ec', 'u.employee_category_id', '=', 'ec.id')
            ->select(
                'u.user_id',
                'u.prefix',
                'u.first_name',
                'u.last_name',
                'u.email',
                'u.id_card_number',
                'u.phone_number',
                'u.branch_id',
                'u.position_id',
                'u.employment_type_id',
                'u.employee_category_id',
                'u.status',
                'u.temp_position_end_date',
                'u.original_position_id',
                'u.created_at',
                'u.updated_at',
                'b.branch_name',
                'p.position_name',
                'et.name as employment_type_name',
                'ec.name as employee_category_name'
            );
    }

    public function index()
    {
        try {
            return response()->json($this->employeeQuery()->get());
        } catch (\Exception $e) {
            return response()->json(['message' => 'Database Error: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $employee = $this->employeeQuery()->where('u.user_id', $id)->first();
            if (!$employee) return response()->json(['message' => 'Not Found'], 404);
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

        // ลบ field ที่เป็น null ออก เพื่อไม่ให้ทับค่าเดิมในฐานข้อมูล
        foreach (['branch_id', 'position_id', 'employment_type_id', 'employee_category_id'] as $field) {
            if (is_null($validated[$field] ?? null) || ($validated[$field] ?? '') === '') {
                unset($validated[$field]);
            }
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