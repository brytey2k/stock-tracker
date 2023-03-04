<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AuthTest extends TestCase
{

    use RefreshDatabase;

    public function testUserCanRegister(): void
    {
        $response = $this->post(route('auth.register'), [
            'name' => 'First Name',
            'email' => 'valid@email.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertOk();
        $response->assertJson(function(AssertableJson $json) {
            $json->hasAll(['status', 'message', 'data']);
        });
    }

    public function testUserWithCorrectCredentialsCanLogin(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
    }

    public function testUserWithIncorrectCredentailsCannotLogin(): void
    {
        $response = $this->post(route('auth.login'), [
            'email' => 'invalid@email.com',
            'password' => 'password',
        ]);

        $response->assertStatus(401);
    }
}
