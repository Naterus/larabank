<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class AuthController extends Controller
{

    public function accountNumberGenerator($length = 10){
        $number_pattern = "0123456789";
        $number_pattern_length = strlen($number_pattern);
        $account_number = 71; //All account numbers would begin with 71

        for ($l = 1; $l <= $length; $l++){
            $account_number = $account_number.$number_pattern[rand(0, $number_pattern_length -1)];
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

        $account_number = $this->accountNumberGenerator(10);
        $bonus = 20000;
        $find_existing_account = Account::where("account_number",$account_number)->count();

        while($find_existing_account > 0) {
            //Call function recursively when generated account number already exists.
            $this->createAccount($request);
        }

        DB::beginTransaction();

        try {

            //Create Login Profile
            $create_user = DB::table("users")->insert([
                "email" => $request->input("email"),
                "password" => bcrypt($request->input("password")),
            ]);

            //Create Account
            $create_account = DB::table("accounts")->insert([
                "account_name" => $request->input("account_name"),
                "account_number" => $account_number,
                "account_type" => $request->input("account_type"),
                "account_balance" => $bonus,
                "phone_number" => $request->input("phone_number"),
                "user_email" => $request->input("email")
            ]);

            //
            DB::commit();


            //Create New transaction record for bonus credit
            DB::table("transactions")->insert([
                "transaction_type" => "Credit",
                "transaction_description" => "@Signup Bonus / Credit / @LaraBank",
                "transaction_amount" => $bonus,
                "account_id" => $create_account->id
            ]);

            DB::commit();
        }
        catch (\Exception $e) {

            DB::rollback();

            return $e;
        }

        //Sign user into account
        Auth::attempt([
            "email" => $request->input("email"),
            "password" => $request->input("password"),
            "status" => 1,
        ]);

        return redirect()->route("account.dashboard")->with("success","Welcome, You were credited with $".$bonus." for free.");

    }

}
