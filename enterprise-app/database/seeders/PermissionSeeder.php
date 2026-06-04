<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            'HR'      => ['view', 'create', 'update', 'delete'],
            'Saraban' => ['view', 'create', 'update', 'delete', 'approve'],
            'Budget'  => ['view', 'create', 'update', 'delete', 'approve'],
            'Core'    => ['view', 'create', 'update', 'delete'],
        ];

        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                \App\Models\Permission::firstOrCreate(
                    ['module' => $module, 'action' => $action],
                    ['description' => "{$action} {$module} records"]
                );
            }
        }

        // Ensure super_admin role exists
        $superAdmin = \App\Models\Role::firstOrCreate(
            ['role_name' => 'super_admin'],
            ['description' => 'Full access to all modules and branches', 'legacy_permissions' => []]
        );

        // Ensure hr_admin role exists with HR permissions
        $hrAdmin = \App\Models\Role::firstOrCreate(
            ['role_name' => 'hr_admin'],
            ['description' => 'Full access to HR module', 'legacy_permissions' => []]
        );

        $hrPermissions = \App\Models\Permission::where('module', 'HR')->pluck('id');
        $hrAdmin->permissions()->syncWithoutDetaching($hrPermissions);
    }
}
