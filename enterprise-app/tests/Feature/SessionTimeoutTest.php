<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;

class SessionTimeoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_request_is_rejected_when_session_is_expired(): void
    {
        Config::set('session.lifetime', 1); // 1 minute

        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        // Simulate last_activity 2 minutes ago
        $user->tokens()->latest()->first()->forceFill([
            'last_used_at' => now()->subMinutes(2),
        ])->save();

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/user');

        $response->assertStatus(401);
    }

    public function test_active_session_passes_through(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/user');

        $response->assertStatus(200);
    }
}
