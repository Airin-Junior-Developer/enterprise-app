<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\AuditLog;
use App\Models\Hr\Branch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_a_branch_writes_audit_log(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        Branch::create(['branch_name' => 'Test Branch', 'description' => 'For testing']);

        $log = AuditLog::where('action', 'create')
            ->where('model_type', Branch::class)
            ->first();

        $this->assertNotNull($log);
        $this->assertEquals($user->user_id, $log->user_id);
        $this->assertEquals('HR', $log->module);
    }

    public function test_updating_a_branch_logs_old_and_new_values(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $branch = Branch::create(['branch_name' => 'Old Name', 'description' => '']);
        $branch->update(['branch_name' => 'New Name']);

        $log = AuditLog::where('action', 'update')
            ->where('model_type', Branch::class)
            ->first();

        $this->assertNotNull($log);
        $this->assertArrayHasKey('branch_name', $log->old_values);
        $this->assertArrayHasKey('branch_name', $log->new_values);
    }

    public function test_deleting_a_branch_writes_delete_audit_log(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $branch = Branch::create(['branch_name' => 'To Delete', 'description' => '']);
        $branch->delete();

        $log = AuditLog::where('action', 'delete')
            ->where('model_type', Branch::class)
            ->first();

        $this->assertNotNull($log);
    }
}
