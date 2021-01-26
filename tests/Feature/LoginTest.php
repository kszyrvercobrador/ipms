<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login()
    {
        $user = User::factory()->create();

        $response = $this->json('POST', route('auth.login'), [
            'email' => $user->email,
            'password' => 'password',
        ])
            ->assertOk()
            ->assertJsonStructure([
                'access_token', 'user' => [
                    'id', 'name', 'email'
                ]
            ]);

        $responseData = $response->getData();
        // Respose should return access_token and user resource
        $response->assertJsonFragment([
            'access_token' => $responseData->access_token,
            'user' => UserResource::make($user)->resolve(),
        ]);
        // It should create personal_access_tokens
        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_type' => User::class,
            'tokenable_id' => $user->id,
        ]);
    }

    public function test_invalid_email()
    {
        // Email not provided
        $this->json('POST', route('auth.login'), [
            'email' => '',
            'password' => 'password',
        ])->assertStatus(422)->assertJsonStructure([
            'errors' => ['email']
        ]);
        // Email does not exist
        $this->json('POST', route('auth.login'), [
            'email' => 'fake@example.com',
            'password' => 'password',
        ])->assertStatus(422)->assertJsonStructure([
            'errors' => ['email']
        ]);
    }

    public function test_invalid_password()
    {
        $user = User::factory()->create();
        // Send an empty password
        $this->json('POST', route('auth.login'), [
            'email' => $user->email,
            'password' => '',
        ])->assertStatus(422)->assertJsonStructure([
            'errors' => ['password']
        ]);
        // Password provided does not match
        $this->json('POST', route('auth.login'), [
            'email' => $user->email,
            'password' => 'not_valid_password',
        ])->assertStatus(422)->assertJsonStructure([
            'errors' => ['email']
        ]);
    }
}
