<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\URL;

class EmailVerificationTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
   public function test_registration_sends_email_to_mailhog()
{
    $this->post('/register', [
        'name' => 'テストユーザー',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
    ]);

    $user = User::where('email', 'test@example.com')->first();

    $this->assertNotNull($user);

    $this->assertNull($user->email_verified_at);

}

    public function test_email_verification_flow_from_notice_page()
{
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $this->actingAs($user);

    $response = $this->get('/email/verify');

    $response->assertStatus(200);

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        [
            'id' => $user->id,
            'hash' => sha1($user->email),
        ]
    );

    $response = $this->get($verificationUrl);

    $this->assertNotNull($user->fresh()->email_verified_at);
}

    public function test_email_verification_redirects_to_profile_page()
{
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $this->actingAs($user);

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        [
            'id' => $user->id,
            'hash' => sha1($user->email),
        ]
    );

    $response = $this->get($verificationUrl);
    $response->assertRedirectContains('/mypage/profile');

    $this->assertNotNull($user->fresh()->email_verified_at);

    $this->get('/mypage/profile')->assertStatus(200);
}
}
