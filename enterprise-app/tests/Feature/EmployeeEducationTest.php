<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Hr\EmployeeEducation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeEducationTest extends TestCase
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

    public function test_hr_admin_can_list_education_records(): void
    {
        $admin = $this->makeHrAdmin();
        $employee = User::factory()->create();
        EmployeeEducation::create([
            'user_id'          => $employee->user_id,
            'institution_name' => 'Chulalongkorn University',
            'degree_level'     => "Bachelor's",
            'field_of_study'   => 'Computer Science',
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson("/api/employees/{$employee->user_id}/education");

        $response->assertOk()->assertJsonCount(1);
    }

    public function test_hr_admin_can_create_education_record(): void
    {
        $admin = $this->makeHrAdmin();
        $employee = User::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson("/api/employees/{$employee->user_id}/education", [
                'institution_name' => 'Kasetsart University',
                'degree_level'     => "Master's",
                'field_of_study'   => 'Information Technology',
                'graduation_year'  => 2020,
                'gpa'              => 3.75,
            ]);

        $response->assertCreated();
        $this->assertDatabaseHas('employee_educations', [
            'user_id'          => $employee->user_id,
            'institution_name' => 'Kasetsart University',
        ]);
    }

    public function test_hr_admin_can_update_education_record(): void
    {
        $admin = $this->makeHrAdmin();
        $employee = User::factory()->create();
        $record = EmployeeEducation::create([
            'user_id'          => $employee->user_id,
            'institution_name' => 'Old University',
            'degree_level'     => "Bachelor's",
            'field_of_study'   => 'Physics',
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->putJson("/api/employees/{$employee->user_id}/education/{$record->id}", [
                'institution_name' => 'Updated University',
                'degree_level'     => "Master's",
                'field_of_study'   => 'Physics',
            ]);

        $response->assertOk();
        $this->assertDatabaseHas('employee_educations', [
            'id'               => $record->id,
            'institution_name' => 'Updated University',
        ]);
    }

    public function test_hr_admin_can_delete_education_record(): void
    {
        $admin = $this->makeHrAdmin();
        $employee = User::factory()->create();
        $record = EmployeeEducation::create([
            'user_id'          => $employee->user_id,
            'institution_name' => 'Test University',
            'degree_level'     => "Bachelor's",
            'field_of_study'   => 'Math',
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/employees/{$employee->user_id}/education/{$record->id}");

        $response->assertOk();
        $this->assertDatabaseMissing('employee_educations', ['id' => $record->id]);
    }
}
