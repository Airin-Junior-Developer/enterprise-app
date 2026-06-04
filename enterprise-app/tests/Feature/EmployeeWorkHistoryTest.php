<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Hr\EmployeeWorkHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeWorkHistoryTest extends TestCase
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

    public function test_hr_admin_can_list_work_history(): void
    {
        $admin = $this->makeHrAdmin();
        $employee = User::factory()->create();
        EmployeeWorkHistory::create([
            'user_id'        => $employee->user_id,
            'company_name'   => 'ACME Corp',
            'position_title' => 'Developer',
            'start_date'     => '2020-01-01',
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson("/api/employees/{$employee->user_id}/work-history");

        $response->assertOk()->assertJsonCount(1);
    }

    public function test_hr_admin_can_create_work_history(): void
    {
        $admin = $this->makeHrAdmin();
        $employee = User::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson("/api/employees/{$employee->user_id}/work-history", [
                'company_name'   => 'Tech Ltd',
                'position_title' => 'Senior Developer',
                'start_date'     => '2019-06-01',
                'end_date'       => '2022-12-31',
            ]);

        $response->assertCreated();
        $this->assertDatabaseHas('employee_work_histories', [
            'user_id'      => $employee->user_id,
            'company_name' => 'Tech Ltd',
        ]);
    }

    public function test_create_work_history_rejects_end_date_before_start_date(): void
    {
        $admin = $this->makeHrAdmin();
        $employee = User::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson("/api/employees/{$employee->user_id}/work-history", [
                'company_name'   => 'Bad Dates Co',
                'position_title' => 'Tester',
                'start_date'     => '2022-06-01',
                'end_date'       => '2021-01-01',
            ]);

        $response->assertStatus(422);
    }

    public function test_hr_admin_can_update_work_history(): void
    {
        $admin = $this->makeHrAdmin();
        $employee = User::factory()->create();
        $record = EmployeeWorkHistory::create([
            'user_id'        => $employee->user_id,
            'company_name'   => 'Old Co',
            'position_title' => 'Junior Dev',
            'start_date'     => '2018-01-01',
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->putJson("/api/employees/{$employee->user_id}/work-history/{$record->id}", [
                'company_name'   => 'New Co',
                'position_title' => 'Senior Dev',
                'start_date'     => '2018-01-01',
            ]);

        $response->assertOk();
        $this->assertDatabaseHas('employee_work_histories', [
            'id'           => $record->id,
            'company_name' => 'New Co',
        ]);
    }

    public function test_hr_admin_can_delete_work_history(): void
    {
        $admin = $this->makeHrAdmin();
        $employee = User::factory()->create();
        $record = EmployeeWorkHistory::create([
            'user_id'        => $employee->user_id,
            'company_name'   => 'Delete Me Ltd',
            'position_title' => 'Temp',
            'start_date'     => '2021-01-01',
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/employees/{$employee->user_id}/work-history/{$record->id}");

        $response->assertOk();
        $this->assertDatabaseMissing('employee_work_histories', ['id' => $record->id]);
    }

    public function test_employee_can_read_their_own_work_history(): void
    {
        $employee = User::factory()->create();
        EmployeeWorkHistory::create([
            'user_id'        => $employee->user_id,
            'company_name'   => 'Own Co',
            'position_title' => 'Developer',
            'start_date'     => '2020-01-01',
        ]);

        $response = $this->actingAs($employee, 'sanctum')
            ->getJson("/api/employees/{$employee->user_id}/work-history");

        $response->assertOk()->assertJsonCount(1);
    }

    public function test_employee_cannot_read_another_employees_work_history(): void
    {
        $employee = User::factory()->create();
        $other    = User::factory()->create();
        EmployeeWorkHistory::create([
            'user_id'        => $other->user_id,
            'company_name'   => 'Other Co',
            'position_title' => 'Manager',
            'start_date'     => '2019-01-01',
        ]);

        $response = $this->actingAs($employee, 'sanctum')
            ->getJson("/api/employees/{$other->user_id}/work-history");

        $response->assertForbidden();
    }
}
