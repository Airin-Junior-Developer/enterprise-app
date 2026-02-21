<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // ✅ อย่าลืมเพิ่มบรรทัดนี้
use App\Models\Hr\Branch;

class BranchController extends Controller
{
    // 1. ดึงข้อมูลสาขา + รายชื่อ HR ผู้รับผิดชอบ
    public function index()
    {
        // 1.1 ดึงสาขาทั้งหมด เรียงตามชื่อ
        $branches = Branch::orderBy('branch_name')->get();

        // 1.2 ดึงรายชื่อพนักงานที่เป็น "HR" หรือ "Admin" ทั้งหมดออกมาเตรียมไว้
        // (เทคนิคนี้เรียกว่า Eager Loading แบบ Manual เพื่อลด Query ไม่ให้ Database ทำงานหนัก)
        $hrUsers = DB::table('users')
            ->join('positions', 'users.position_id', '=', 'positions.position_id')
            ->where(function ($query) {
                $query->where('positions.position_name', 'LIKE', '%HR%')        // ตำแหน่งที่มีคำว่า HR
                    ->orWhere('positions.position_name', 'LIKE', '%Admin%');  // หรือตำแหน่ง Admin (เผื่อ Super Admin)
            })
            ->select(
                'users.user_id',
                'users.first_name',
                'users.last_name',
                'users.branch_id',
                'positions.position_name'
            )
            ->get();

        // 1.3 วนลูปเอา HR ไปใส่ให้ตรงกับสาขาของตัวเอง
        foreach ($branches as $branch) {
            // สร้าง key ใหม่ชื่อ 'responsible_users' ให้ Vue เอาไปใช้
            $branch->responsible_users = $hrUsers->where('branch_id', $branch->branch_id)->values();
        }

        return response()->json($branches);
    }

    // 2. สร้างสาขาใหม่ (เหมือนเดิม)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_name' => 'required|string|max:255|unique:branches,branch_name',
            'address' => 'nullable|string'
        ]);

        Branch::create($validated);

        return response()->json(['message' => 'เพิ่มสาขาสำเร็จ'], 201);
    }

    // 3. แก้ไขข้อมูลสาขา (เหมือนเดิม)
    public function update(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);

        $validated = $request->validate([
            'branch_name' => 'required|string|max:255|unique:branches,branch_name,' . $id . ',branch_id',
            'address' => 'nullable|string'
        ]);

        $branch->update($validated);

        return response()->json(['message' => 'แก้ไขข้อมูลสำเร็จ']);
    }

    // 4. ลบสาขา (เหมือนเดิม)
    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);

        if ($branch->users()->count() > 0) {
            return response()->json(['message' => 'ไม่สามารถลบได้ เนื่องจากมีพนักงานสังกัดสาขานี้อยู่'], 400);
        }

        $branch->delete();

        return response()->json(['message' => 'ลบข้อมูลสำเร็จ']);
    }
}