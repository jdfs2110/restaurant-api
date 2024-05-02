<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use DatabaseTransactions;
    public function test_incorrect_login(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])->post('/api/login', ['email' => 'test@test.com', 'password' => '12345']);

        $response
            ->assertStatus(400)
            ->assertJson(['error' => 'Usuario o contraseña incorrectos.']);
    }

    public function test_successful_login(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])->post('/api/login', ['email' => 'test@test.com', 'password' => '123456']);

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(['data', 'token'])
            );
    }

    public function test_incorrect_register(): void
    {
        $data = [
            'name' => 'already existing user',
            'email' => 'test@test.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'id_rol' => 1
        ];
        $response = $this->withHeaders(['Accept' => 'application/json'])->post('/api/registro', $data);

        $response
            ->assertStatus(400)
            ->assertJson(fn (AssertableJson $json) =>
            $json->hasAny('error', 'errors')
            );

    }

    public function test_successful_register(): void
    {
        $data = [
            'name' => 'new, non-existing user',
            'email' => 'laravel@test.php',
            'password' => '123456',
            'password_confirmation' => '123456',
            'id_rol' => 1
        ];
        $response = $this->withHeaders(['Accept' => 'application/json'])->post('/api/registro', $data);

        $response
            ->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) =>
            $json->hasAny('data', 'token', 'message')
            );
    }
}
