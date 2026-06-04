<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\EmployeeEducation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeEducationController extends Controller
{
    public function index(Request $request, int $userId): JsonResponse
    {
        $auth = $request->user();
        if ((int) $auth->user_id !== $userId && !$auth->hasPermission('HR', 'create') && !$auth->hasPermission('HR', 'view')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $records = EmployeeEducation::where('user_id', $userId)
            ->orderByDesc('graduation_year')
            ->get();

        return response()->json($records);
    }

    public function store(Request $request, int $userId): JsonResponse
    {
        $validated = $request->validate([
            'institution_name' => 'required|string|max:255',
            'degree_level'     => 'required|string|max:100',
            'field_of_study'   => 'required|string|max:255',
            'graduation_year'  => 'nullable|integer|min:1900|max:' . date('Y'),
            'gpa'              => 'nullable|numeric|min:0|max:4',
            'notes'            => 'nullable|string',
        ]);

        $validated['user_id'] = $userId;
        $record = EmployeeEducation::create($validated);

        return response()->json($record, 201);
    }

    public function update(Request $request, int $userId, int $eduId): JsonResponse
    {
        $record = EmployeeEducation::where('id', $eduId)
            ->where('user_id', $userId)
            ->firstOrFail();

        $validated = $request->validate([
            'institution_name' => 'required|string|max:255',
            'degree_level'     => 'required|string|max:100',
            'field_of_study'   => 'required|string|max:255',
            'graduation_year'  => 'nullable|integer|min:1900|max:' . date('Y'),
            'gpa'              => 'nullable|numeric|min:0|max:4',
            'notes'            => 'nullable|string',
        ]);

        $record->update($validated);

        return response()->json($record);
    }

    public function destroy(int $userId, int $eduId): JsonResponse
    {
        $record = EmployeeEducation::where('id', $eduId)
            ->where('user_id', $userId)
            ->firstOrFail();

        $record->delete();

        return response()->json(['message' => 'ลบข้อมูลสำเร็จ']);
    }
}
