<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function dashboard(){
        return view("account.dashboard");
    }

    public function transferFunds(){
        return view("account.transfer");
    }

    public function submitTransfer(Request $request){

        $this->validate($request,[
            "account_number" => "required|min:12|max:12",
            "amount" => "required|min:10|max:10000"
        ]);

        $amount = $request->input("amount");
        $account_number = $request->input("account_number");

        $check_balance =  Account::where("user_email",Auth::user()->email)
                          ->where("account_balance",">",$amount)
                          ->first();

        if($check_balance){
            //Debit from account
            $check_balance->balance = $check_balance->balance - $amount;
            $check_balance->save();

            //Add debit to transactions
            Transaction::create([
                "transaction_type" => "Debit",
                "transaction_description" => "@Transfer / Debit / @".$account_number,
                "transaction_amount" => $amount,
                "account_id" => Auth::user()->account->id
            ]);

            //Credit Beneficiary account as well.

            return redirect()->back()->with([
                "success" => "Transaction Successful!."
            ]);
        }

        return redirect()->back()->with([
            "error" => "Insufficient Funds!, Transaction declined."
        ]);
    }
}
