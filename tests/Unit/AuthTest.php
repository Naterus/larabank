<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use  RefreshDatabase;

    public function testValidUserLogin()
    {
        //Create test user from factory
        User::factory(1)->create();

        $credentials = [
            "email" => "test@gmail.com",
            "password" => "1234",
        ];

        $response = $this->post(route("login.attempt"),$credentials);

        $response->assertRedirect(route("account.dashboard"));
    }

    public function testInValidUserLogin()
    {

        $credentials = [
            "email" => "johndoe@gmail.com",
            "password" => "1111",
        ];

        $response = $this->post(route("login.attempt"),$credentials);

        $response->assertSessionHas("error");
    }
}
