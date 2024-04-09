<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Verified;
use Database\Factories\UserFactory;

class ApiControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testVerifyEmail()
    {
        $user = User::factory()->create(['email_verified_at' => null]);
        $token = sha1($user->getEmailForVerification());

        $response = $this->get('/api/verify', [
            'id' => $user->id,
            'hash' => $token,
        ]);

        $response->assertStatus(200);
        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function testForgotPassword()
    {
        $user = User::factory()->create();

        $response = $this->post('/api/forgot-password', ['email' => $user->email]);

        $response->assertStatus(200);
        $this->assertNotNull(Password::getRepository()->getPasswordReset($user));
    }

    public function testResetPassword()
    {
        $user = User::factory()->create();
        $token = Password::getRepository()->create($user);

        $response = $this->post('/api/reset-password', [
            'email' => $user->email,
            'token' => $token,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(200);
        $this->assertTrue(Hash::check('newpassword', $user->fresh()->password));
    }

    public function testSendResetLinkEmail()
    {
        $user = User::factory()->create();

        $response = $this->post('/api/send-reset-link-email', ['email' => $user->email]);

        $response->assertRedirect();
        $response->assertSessionHas('status');
    }
}
