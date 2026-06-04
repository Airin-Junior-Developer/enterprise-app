<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionRbacTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_with_permission_can_access_protected_route(): void
    {
        $permission = Permission::create([
            'module' => 'HR',
            'action' => 'create',
            'description' => 'Create employees',
        ]);

        $role = Role::create([
            'role_name' => 'hr_manager',
            'description' => 'HR Manager',
            'legacy_permissions' => [],
        ]);
        $role->permissions()->attach($permission->id);

        $user = User::factory()->create();
        $user->roles()->attach($role->role_id);

        $this->assertTrue($user->hasPermission('HR', 'create'));
    }

    public function test_user_without_permission_is_denied(): void
    {
        $user = User::factory()->create();
        $this->assertFalse($user->hasPermission('HR', 'delete'));
    }

    public function test_super_admin_role_bypasses_all_permission_checks(): void
    {
        $role = Role::create([
            'role_name' => 'super_admin',
            'description' => 'Super Administrator',
            'legacy_permissions' => [],
        ]);
        $user = User::factory()->create();
        $user->roles()->attach($role->role_id);

        $this->assertTrue($user->isSuperAdmin());
        $this->assertTrue($user->hasPermission('Budget', 'delete'));
    }
}
