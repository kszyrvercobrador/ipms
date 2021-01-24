<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function postData(): array
    {
        return [
            'name'                  => $this->faker()->name(),
            'email'                 => $this->faker()->safeEmail(),
            'password'              => $password = $this->faker()->password(),
            'password_confirmation' => $password,
        ];
    }

    public function test_user_registration()
    {
        $postData = $this->postData();
        $this->json('POST', route('register'), $postData)
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'email']
            ]);

        // Check if the user existed on the database
        $this->assertDatabaseHas('users', [
            'name'  => $postData['name'],
            'email' => $postData['email'],
        ]);
    }

    public function test_user_registration_name_validation()
    {
        // name is not provided
        $response = $this->json('POST', route('register'), array_merge(
            $this->postData(),
            ['name' => '']
        ))->assertStatus(422)->assertJsonStructure([
            'errors' => ['name'], 'message'
        ]);
    }

    public function test_user_registration_email_validation()
    {
        // email is not provided
        $this->json('POST', route('register'), array_merge(
            $this->postData(),
            ['email' => '']
        ))->assertStatus(422)->assertJsonStructure([
            'errors' => ['email'], 'message'
        ]);

        // provide an invalid email
        $this->json('POST', route('register'), array_merge(
            $this->postData(),
            ['email' => 'not_a_valid_email']
        ))->assertStatus(422)->assertJsonStructure([
            'errors' => ['email'], 'message'
        ]);
    }

    public function test_user_registration_password_validation()
    {
        // password is not provided
        $this->json('POST', route('register'), array_merge(
            $this->postData(),
            ['password' => '']
        ))->assertStatus(422)->assertJsonStructure([
            'errors' => ['password'], 'message'
        ]);

        // password is not confirmed
        $this->json('POST', route('register'), array_merge(
            $this->postData(),
            ['password_confirmation' => 'not_a_valid_password']
        ))->assertStatus(422)->assertJsonStructure([
            'errors' => ['password'], 'message'
        ]);
    }
}
