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
        //Disable exception handling to get descriptive error messages
        $this->withoutExceptionHandling();

        //Create test user from factory
        User::factory(1)->create();

        $credentials = [
            "email" => "test@gmail.com",
            "password" => "1234",
        ];

        $response = $this->post(route("login.attempt"),$credentials);

        $response->assertRedirect(route("account.dashboard"));
    }

    public function testInvalidUserLogin()
    {
        //Disable exception handling to get descriptive error messages
        $this->withoutExceptionHandling();

        $credentials = [
            "email" => "johndoe@gmail.com",
            "password" => "1111",
        ];

        $response = $this->post(route("login.attempt"),$credentials);

        $response->assertRedirect(route("home"));
        $response->assertSessionHas("error");
    }

    public function testUniqueEmailOnRegistration(){

        //Create test user from factory
        User::factory(1)->create();

        $credentials = [
            "email" => "test@gmail.com",
            "password" => "111111",
            "password_confirmation" => "111111",
            "account_type" => "Savings",
            "account_name" => "John Doe",
            "phone_number" => "08133093344",
        ];

        $response = $this->post(route("register.submit"),$credentials);

        $response->assertSessionHasErrors("email");

    }
}
