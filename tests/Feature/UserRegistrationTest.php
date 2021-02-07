<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use  RefreshDatabase;

    public function testRegistrationPage(){

        //Disable exception handling to get descriptive error messages
        $this->withoutExceptionHandling();

        $response  = $this->get(route("register"));

        $response->assertViewIs("register");

    }

    public function testRegistrationFormSubmission()
    {
        //Disable exception handling to get descriptive error messages
        $this->withoutExceptionHandling();

        $credentials = [
            "email" => "johndoe@gmail.com",
            "password" => "111111",
            "password_confirmation" => "111111",
            "account_type" => "Savings",
            "account_name" => "John Doe",
            "phone_number" => "08133093344",
        ];

        $response = $this->post(route("register.submit"),$credentials);

        $response->assertRedirect(route("account.dashboard"));
        $response->assertSessionHas("success");
    }

}
