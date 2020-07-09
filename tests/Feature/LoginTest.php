<?php

namespace Tests\Feature;

use App\Role;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use LaratrustSeeder;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function validate_email_is_required()
    {
        $response = $this->postJson('/api/login', [
            'email' => null,
            'password' => 'password'
        ]);

        $response->assertJsonValidationErrors(['email' =>'The email field is required.']);
    }

    /**
     * @test
     */
    public function validate_email_is_valid()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'not_email',
            'password' => 'password'
        ]);

        $response->assertJsonValidationErrors(['email' => 'The email must be a valid email address.']);
    }

    /**
     * @test
     */
    public function validate_password_is_required()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'john@doe.com',
            'password' => null
        ]);

        $response->assertJsonValidationErrors(['password' => 'The password field is required.']);
    }

    /**
     * @test
     */
    public function validate_password_is_string()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'john@app.com',
            'password' => 123
        ]);

        $response->assertJsonValidationErrors(['password' => 'The password must be a string']);
    }

    /**
     * @test
     */
    public function validate_email_password_mismatch()
    {
        $this->seed(LaratrustSeeder::class);

        $user = factory(User::class)->create();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'asdasd',
        ]);

        // dd($response->getContent());

        $response->assertStatus(401)->assertExactJson(['error' => 'Unauthorized']);
    }

    /**
     * @test
     */
    public function login_success()
    {
        $this->seed(LaratrustSeeder::class);

        $user = factory(User::class)->create();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure(['access_token', 'token_type', 'expires_in']);
        ;
    }
}
