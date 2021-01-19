<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function accountNumberGenerator($length = 12){
        $number_pattern = "0123456789";
        $account_number = null;

        for ($l = 1; $l <= $length; $l++){
           $account_number = $account_number.$number_pattern[rand(0, $length -1)];
        }

        return $account_number;
    }

    public function createAccount(Request $request)
    {
       $this->validate($request,[
           "account_name" => "required|string|min:6|max:80",
           "account_type" => "required",
           "phone_number" => "required|min:10|max:11",
           "email" => "required|email|unique:users",
           "password" => "required|min:6|confirmed",
           ]);

       return $this->accountNumberGenerator(12);



    }
}
