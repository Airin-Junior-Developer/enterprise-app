<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\EmployeeSalaryHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeSalaryHistoryController extends Controller
{
    public function index(Request $request, int $userId): JsonResponse
    {
        $auth = $request->user();
        if ((int) $auth->user_id !== $userId && !$auth->hasPermission('HR', 'create') && !$auth->hasPermission('HR', 'view')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $records = EmployeeSalaryHistory::where('user_id', $userId)
            ->with('recorder:user_id,first_name,last_name')
            ->orderByDesc('effective_date')
            ->get();

        return response()->json($records);
    }

    public function store(Request $request, int $userId): JsonResponse
    {
        $validated = $request->validate([
            'effective_date' => 'required|date',
            'old_salary'     => 'required|numeric|min:0',
            'new_salary'     => 'required|numeric|min:0',
            'promotion_type' => 'required|in:step_increment,level_promotion,qualification_adjustment,special_adjustment',
            'notes'          => 'nullable|string',
        ]);

        $validated['user_id']     = $userId;
        $validated['recorded_by'] = auth()->id();

        $record = EmployeeSalaryHistory::create($validated);

        return response()->json($record, 201);
    }

    public function destroy(int $userId, int $salId): JsonResponse
    {
        $record = EmployeeSalaryHistory::where('id', $salId)
            ->where('user_id', $userId)
            ->firstOrFail();

        $record->delete();

        return response()->json(['message' => 'ลบข้อมูลสำเร็จ']);
    }
}
