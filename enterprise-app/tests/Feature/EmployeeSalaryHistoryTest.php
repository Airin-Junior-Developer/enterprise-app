<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Hr\EmployeeSalaryHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeSalaryHistoryTest extends TestCase
{
    use RefreshDatabase;

    private function makeHrAdmin(): User
    {
        $permission = Permission::create([
            'module' => 'HR',
            'action' => 'create',
            'description' => 'HR Admin',
        ]);
        $role = Role::create([
            'role_name' => 'hr_admin',
            'description' => 'HR Admin',
            'legacy_permissions' => [],
        ]);
        $role->permissions()->attach($permission->id);
        $admin = User::factory()->create();
        $admin->roles()->attach($role->role_id);
        return $admin;
    }

    public function test_hr_admin_can_list_salary_history(): void
    {
        $admin = $this->makeHrAdmin();
        $employee = User::factory()->create();
        EmployeeSalaryHistory::create([
            'user_id'        => $employee->user_id,
            'effective_date' => '2024-01-01',
            'old_salary'     => 20000.00,
            'new_salary'     => 22000.00,
            'promotion_type' => 'step_increment',
            'recorded_by'    => $admin->user_id,
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson("/api/employees/{$employee->user_id}/salary-history");

        $response->assertOk()->assertJsonCount(1);
    }

    public function test_hr_admin_can_create_salary_history_and_recorded_by_is_autofilled(): void
    {
        $admin = $this->makeHrAdmin();
        $employee = User::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson("/api/employees/{$employee->user_id}/salary-history", [
                'effective_date' => '2025-01-01',
                'old_salary'     => 22000.00,
                'new_salary'     => 25000.00,
                'promotion_type' => 'level_promotion',
            ]);

        $response->assertCreated();
        $this->assertDatabaseHas('employee_salary_histories', [
            'user_id'        => $employee->user_id,
            'promotion_type' => 'level_promotion',
            'recorded_by'    => $admin->user_id,
        ]);
    }

    public function test_hr_admin_can_delete_salary_history(): void
    {
        $admin = $this->makeHrAdmin();
        $employee = User::factory()->create();
        $record = EmployeeSalaryHistory::create([
            'user_id'        => $employee->user_id,
            'effective_date' => '2024-06-01',
            'old_salary'     => 18000.00,
            'new_salary'     => 20000.00,
            'promotion_type' => 'special_adjustment',
            'recorded_by'    => $admin->user_id,
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/employees/{$employee->user_id}/salary-history/{$record->id}");

        $response->assertOk();
        $this->assertDatabaseMissing('employee_salary_histories', ['id' => $record->id]);
    }

    public function test_employee_can_read_their_own_salary_history(): void
    {
        $employee = User::factory()->create();
        EmployeeSalaryHistory::create([
            'user_id'        => $employee->user_id,
            'effective_date' => '2024-01-01',
            'old_salary'     => 20000.00,
            'new_salary'     => 22000.00,
            'promotion_type' => 'step_increment',
        ]);

        $response = $this->actingAs($employee, 'sanctum')
            ->getJson("/api/employees/{$employee->user_id}/salary-history");

        $response->assertOk()->assertJsonCount(1);
    }

    public function test_employee_cannot_read_another_employees_salary_history(): void
    {
        $employee = User::factory()->create();
        $other    = User::factory()->create();
        EmployeeSalaryHistory::create([
            'user_id'        => $other->user_id,
            'effective_date' => '2023-01-01',
            'old_salary'     => 15000.00,
            'new_salary'     => 18000.00,
            'promotion_type' => 'step_increment',
        ]);

        $response = $this->actingAs($employee, 'sanctum')
            ->getJson("/api/employees/{$other->user_id}/salary-history");

        $response->assertForbidden();
    }
}
