<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
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
        $find_existing_account_number = Account::where("account_number",$account_number)->count();

        if($find_existing_account_number > 0) {
            //Call function recursively when generated account number already exists.
            $this->createAccount($request);
        }

        try {

            //Create Login Profile
            $create_user = User::create([
                "email" => $request->input("email"),
                "password" => bcrypt($request->input("password")),
            ]);

            //Create Account
            $create_account = Account::create([
                "account_name" => $request->input("account_name"),
                "account_number" => $account_number,
                "account_type" => $request->input("account_type"),
                "account_balance" => $bonus,
                "phone_number" => $request->input("phone_number"),
                "user_email" => $create_user->email
            ]);

            //Create New transaction record for bonus credit
            Transaction::create([
                "transaction_type" => "Credit",
                "transaction_description" => "@Signup Bonus / Credit / @Larabank",
                "transaction_amount" => $bonus,
                "account_id" => $create_account->id
            ]);

        }

        catch (\Exception $e) {

            return $e;
        }

        //Sign user into account
        Auth::attempt([
            "email" => $request->input("email"),
            "password" => $request->input("password"),
            "status" => 1,
        ]);

        return redirect()->route("account.dashboard")->with("success","Welcome ".strtok(trim(Auth::user()->account->account_name), ' ').", You were credited with &#8358;".number_format($bonus)." as bonus from larabank.");

    }

    public function loginAccount(Request $request) {

        $this->validate($request,[
            "email" => "required|email",
            "password" => "required"
        ]);


        if ( Auth::attempt ([
            "email" => $request->input("email"),
            "password" => $request->input("password"),
            "status" => 1,
        ])) {

            return redirect()->route('account.dashboard');

        }
        else {
            return redirect()->back()->with(["error" => "Email or password incorrect."]);
        }
    }

    public function logout(){
        Auth::logout();

        return redirect()->route("home");
    }

}
