<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;

class GoogleOAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_existing_user_receives_token_on_google_callback(): void
    {
        $user = User::factory()->create(['email' => 'employee@example.com']);

        $socialiteUser = $this->createMock(SocialiteUser::class);
        $socialiteUser->method('getEmail')->willReturn('employee@example.com');
        $socialiteUser->method('getId')->willReturn('google-id-123');

        Socialite::shouldReceive('driver->stateless->user')
            ->once()
            ->andReturn($socialiteUser);

        $response = $this->getJson('/api/auth/google/callback');

        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'user']);
    }

    public function test_unknown_email_is_rejected_on_google_callback(): void
    {
        $socialiteUser = $this->createMock(SocialiteUser::class);
        $socialiteUser->method('getEmail')->willReturn('outsider@example.com');
        $socialiteUser->method('getId')->willReturn('google-id-456');

        Socialite::shouldReceive('driver->stateless->user')
            ->once()
            ->andReturn($socialiteUser);

        $response = $this->getJson('/api/auth/google/callback');

        $response->assertStatus(403);
    }
}
