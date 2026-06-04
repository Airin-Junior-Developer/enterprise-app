<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\EmployeeWorkHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeWorkHistoryController extends Controller
{
    public function index(Request $request, int $userId): JsonResponse
    {
        $auth = $request->user();
        if ((int) $auth->user_id !== $userId && !$auth->hasPermission('HR', 'create') && !$auth->hasPermission('HR', 'view')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $records = EmployeeWorkHistory::where('user_id', $userId)
            ->orderByDesc('start_date')
            ->get();

        return response()->json($records);
    }

    public function store(Request $request, int $userId): JsonResponse
    {
        $validated = $request->validate([
            'company_name'       => 'required|string|max:255',
            'position_title'     => 'required|string|max:255',
            'start_date'         => 'required|date',
            'end_date'           => 'nullable|date|after_or_equal:start_date',
            'reason_for_leaving' => 'nullable|string|max:255',
            'notes'              => 'nullable|string',
        ]);

        $validated['user_id'] = $userId;
        $record = EmployeeWorkHistory::create($validated);

        return response()->json($record, 201);
    }

    public function update(Request $request, int $userId, int $histId): JsonResponse
    {
        $record = EmployeeWorkHistory::where('id', $histId)
            ->where('user_id', $userId)
            ->firstOrFail();

        $validated = $request->validate([
            'company_name'       => 'required|string|max:255',
            'position_title'     => 'required|string|max:255',
            'start_date'         => 'required|date',
            'end_date'           => 'nullable|date|after_or_equal:start_date',
            'reason_for_leaving' => 'nullable|string|max:255',
            'notes'              => 'nullable|string',
        ]);

        $record->update($validated);

        return response()->json($record);
    }

    public function destroy(int $userId, int $histId): JsonResponse
    {
        $record = EmployeeWorkHistory::where('id', $histId)
            ->where('user_id', $userId)
            ->firstOrFail();

        $record->delete();

        return response()->json(['message' => 'ลบข้อมูลสำเร็จ']);
    }
}
