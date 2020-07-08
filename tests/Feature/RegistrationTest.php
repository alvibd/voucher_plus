<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use LaratrustSeeder;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @test
     * @return void
     */
    public function validate_name_is_required()
    {
        // $this->withExceptionHandling();
        $response = $this->postJson('api/registration',[
            'name' => null,
            'email' => 'isemail@mail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // dd($response->getContent());

        $response->assertStatus(422)
                ->assertExactJson([
                    'message' => 'The given data was invalid.',
                    'errors' => ['name' => ['The name field is required.']]
                ])
        ;
    }

    /**
     * @test
     */
    public function validate_name_is_a_string()
    {
        $response = $this->postJson('api/registration',[
            'name' => 123,
            'email' => 'isemail@mail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(422)
                ->assertExactJson([
                    'message' => 'The given data was invalid.',
                    'errors' => ['name' => ['The name must be a string.']]
                ])
        ;
    }

    /**
     * @test
     */
    public function validate_email_is_valid()
    {
        $response = $this->postJson('api/registration',[
            'name' => 'john doe',
            'email' => 'not_an_email',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(422)
                ->assertExactJson([
                    'message' => 'The given data was invalid.',
                    'errors' => ['email' => ['The email must be a valid email address.']]
                ])
        ;
    }

    /**
     * @test
     */
    public function validate_email_is_required()
    {
        $response = $this->postJson('api/registration',[
            'name' => 'john doe',
            'email' => null,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(422)
                ->assertExactJson([
                    'message' => 'The given data was invalid.',
                    'errors' => ['email' => ['The email field is required.']]
                ])
        ;
    }

    /**
     * @test
     */
    public function validate_email_is_unique()
    {
        $user = factory(User::class)->create();

        // $user->create();

        $response = $this->postJson('api/registration',[
            'name' => 'john doe',
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(422)
                ->assertExactJson([
                    'message' => 'The given data was invalid.',
                    'errors' => ['email' => ['The email has already been taken.']]
                ])
        ;


    }

    /**
     * @test
     */
    public function validate_password_is_required()
    {
        $response = $this->postJson('api/registration',[
            'name' => 'john doe',
            'email' => 'john@mail.com',
            'password' => null,
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(422)
                ->assertExactJson([
                    'message' => 'The given data was invalid.',
                    'errors' => ['password' => ['The password field is required.']]
                ])
        ;
    }

    /**
     * @test
     */
    public function validate_password_is_string()
    {
        $response = $this->postJson('api/registration',[
            'name' => 'john doe',
            'email' => 'john@mail.com',
            'password' => 123,
            'password_confirmation' => 123,
        ]);

        $response->assertStatus(422)
                ->assertExactJson([
                    'message' => 'The given data was invalid.',
                    'errors' => ['password' => ['The password must be a string.', 'The password must be at least 8 characters.']]
                ])
        ;
    }

    /**
     * @test
     */
    public function valiate_password_is_8_characters()
    {
        $response = $this->postJson('api/registration',[
            'name' => 'john doe',
            'email' => 'john@mail.com',
            'password' => 'string',
            'password_confirmation' => 'string',
        ]);

        $response->assertStatus(422)
                ->assertExactJson([
                    'message' => 'The given data was invalid.',
                    'errors' => ['password' => ['The password must be at least 8 characters.']]
                ])
        ;
    }

    /** @ test */
    public function validate_password_confirmation_mathc()
    {
        $response = $this->postJson('api/registration',[
            'name' => 'john doe',
            'email' => 'john@mail.com',
            'password' => 'password',
            'password_confirmation' => 'not_password',
        ]);

        $response->assertJsonValidationErrors([
            'name' => 'john doe',
            'email' => 'john@mail.com',
            'password' => 'password',
            'password_confirmation' => 'not_password',
        ]);

        $response->assertStatus(422)
                ->assertExactJson([
                    'message' => 'The given data was invalid.',
                    'errors' => ['password' => ['The password confirmation does not match.']]
                ])
        ;
    }

    /**
     * @test
     */
    public function registration_success()
    {
        $this->seed(LaratrustSeeder::class);

        $response = $this->postJson('api/registration',[
            'name' => 'john doe',
            'email' => 'john@mail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertDatabaseCount('users', 1);
        $this->assertEquals(1, User::first()->hasRole('user'));

        // dd($response->getContent());

        $response->assertStatus(200)
                ->assertJsonStructure(['access_token', 'token_type', 'expires_in']);
        ;
    }

}
