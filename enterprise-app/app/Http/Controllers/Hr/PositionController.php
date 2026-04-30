<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\Position;
use Illuminate\Support\Facades\DB;


class PositionController extends Controller
{
    // 1. ดึงข้อมูลทั้งหมด
    public function index()
    {
        // เรียงตาม priority_level ก่อน (ผู้ใช้กำหนดเอง)
        // ถ้า priority_level เท่ากัน ให้เรียงตาม level_code (CEO > MGR > SUP > STF) แล้วตามชื่อ
        return response()->json(
            Position::orderBy('priority_level', 'asc')
                ->orderByRaw("FIELD(level_code, 'CEO', 'MGR', 'SUP', 'STF') ASC")
                ->orderBy('position_name', 'asc')
                ->get()
        );
    }

    // 2. สร้างตำแหน่งใหม่
    public function store(Request $request)
    {
        $validated = $request->validate([
            'position_name' => 'required|string|max:255',
            'position_name_en' => 'nullable|string|max:255',
            'level_code' => 'required|string',
            'priority_level' => 'nullable|integer', // เพิ่มฟิลด์รับค่า priority_level
            'employment_type_id' => 'nullable|numeric',
            'employee_category_id' => 'nullable|numeric',
            'min_salary' => 'nullable|numeric',
            'max_salary' => 'nullable|numeric',
            'is_active' => 'boolean'
        ]);

        // ถ้าหน้าบ้านไม่ได้ส่งลำดับมา ให้ตั้งค่าเริ่มต้นเป็น 99 (ความสำคัญต่ำสุด/อยู่ท้ายตาราง)
        if (!isset($validated['priority_level'])) {
            $validated['priority_level'] = 99;
        }

        Position::create($validated);

        return response()->json(['message' => 'สร้างตำแหน่งสำเร็จ']);
    }

    // 3. อัปเดตข้อมูล
    public function update(Request $request, $id)
    {
        $position = Position::findOrFail($id);

        $validated = $request->validate([
            'position_name' => 'required|string|max:255',
            'position_name_en' => 'nullable|string|max:255',
            'level_code' => 'required|string',
            'priority_level' => 'nullable|integer',
            'employment_type_id' => 'nullable|numeric',
            'employee_category_id' => 'nullable|numeric',
            'min_salary' => 'nullable|numeric',
            'max_salary' => 'nullable|numeric',
            'is_active' => 'boolean'
        ]);

        // ถ้าค่าว่างให้เป็น 99
        if (!isset($validated['priority_level'])) {
            $validated['priority_level'] = 99;
        }

        $position->update($validated);

        return response()->json(['message' => 'แก้ไขข้อมูลสำเร็จ']);
    }

    // 4. ลบข้อมูล
    public function destroy($id)
    {
        $position = Position::findOrFail($id);

        // ถ้ามีพนักงานใช้ตำแหน่งนี้อยู่ ห้ามลบ
        if ($position->users()->count() > 0) {
            return response()->json(['message' => 'ไม่สามารถลบได้ เนื่องจากมีพนักงานในตำแหน่งนี้'], 400);
        }

        $position->delete();

        return response()->json(['message' => 'ลบข้อมูลสำเร็จ']);
    }

    public function getManagePositions(Request $request)
    {
        $user = $request->user();
        $user->load('position');

        // 1. เรียกฐานข้อมูลจาก View เป็นค่าเริ่มต้น (ดึงทั้งหมด)
        $query = DB::table('view_manage_positions');

        if ($user->position) {
            $posName = strtolower(trim($user->position->position_name));

            // 2. ถ้าเป็น Super Admin หรือ System Admin ไม่ต้องทำอะไรเพิ่ม (ปล่อยให้ดึงทั้งหมด)
            if (in_array($posName, ['Super Admin', 'System Admin'])) {
                // ข้ามการกรอง ปล่อยให้ $query->get() ทำงานดึงทุกสาขา
            }
            // 3. ถ้าเป็น HR Manager หรือผู้จัดการทั่วไป ให้กรองเฉพาะสาขาของตัวเอง
            else if ($posName === 'hr manager' || $user->position->level_code === 'MGR') {
                $query->join('users', 'view_manage_positions.id', '=', 'users.user_id')
                    ->where('users.branch_id', $user->branch_id)
                    ->select('view_manage_positions.*');
            }
        }

        return response()->json($query->get());
    }
}