<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function account(){
        return view("account.account");
    }

    public function updateAccount(Request $request){
        $this->validate($request,[
            "account_name" => "required|string|min:6|max:80",
            "account_type" => "required",
            "phone_number" => "required|min:10|max:11",
        ]);

        Account::where("user_email",Auth::user()->email)->update([
            "account_name" => $request->input("account_name"),
            "account_type" => $request->input("account_type"),
            "phone_number" => $request->input("phone_number"),
            "alternate_email" => $request->input("alternate_email")
        ]);

        return redirect()->back()->with("success","Account updated successfully");

    }

    public function dashboard(){
        return view("account.dashboard");
    }

    public function transferFunds(){
        return view("account.transfer");
    }

    public function submitTransfer(Request $request){

        $this->validate($request,[
            "account_number" => "required|min:12|max:12",
            "amount" => "required"
        ]);

        $amount = $request->input("amount");
        $minimum_transfer = 100; //used to determine minimum transfer amount allowed.
        $account_number = $request->input("account_number");
        $beneficiary_account = Account::where("account_number",$account_number)->first();

        if(!$beneficiary_account){
            return redirect()->back()->with([
                "error" => "Invalid account number. please enter a valid 12 digit account number starting with 71."
            ]);
        }

        if($account_number == Auth::user()->account->account_number){
            return redirect()->back()->with([
                "error" => "Cannot transfer to self. Transaction declined"
            ]);
        }


        if($amount < $minimum_transfer){

            return redirect()->back()->with([
                "error" => "Can not transfer less than &#8358;".$minimum_transfer.". Transaction declined."
            ]);
        }

        DB::beginTransaction();

        try {

            //Initiate exclusive lock for first user to access this row
            $check_balance = Account::where("user_email", Auth::user()->email)
                ->where("account_balance", ">=", $amount)->lockForUpdate()
                ->first();

            if (!$check_balance) {

                return redirect()->back()->with([
                    "error" => "Insufficient Funds!, Transaction declined."
                ]);
            }

            //Debit from account
            $check_balance->account_balance = $check_balance->account_balance - $amount;
            $check_balance->save();

            //Credit Beneficiary account
            $beneficiary_account->account_balance = $beneficiary_account->account_balance + $amount;
            $beneficiary_account->save();

            //Add debit to transactions
            Transaction::create([
                "transaction_type" => "Debit",
                "transaction_description" => "@Transfer / Debit / @" . $beneficiary_account->account_name,
                "transaction_amount" => $amount,
                "account_id" => Auth::user()->account->id
            ]);

            //Add credit to transactions
            Transaction::create([
                "transaction_type" => "Credit",
                "transaction_description" => "@Transfer / Credit / @" . Auth::user()->account->account_name,
                "transaction_amount" => $amount,
                "account_id" => $beneficiary_account->id
            ]);


            //Explicitly commit transaction
            DB::commit();

            return redirect()->back()->with([
                "success" => "Transaction Successful!."
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function airtime(){
        return view("account.airtime");
    }

    public function purchaseAirtime(Request $request){
        $this->validate($request,[
            "beneficiary" => "required",
            "amount" => "required"
        ]);

        $phone_number = null;
        $min_purchase = 100; //used to determine lowest airtime purchase allowed
        $amount = $request->input("amount");

        if($request->input("beneficiary") == "self"){

            $phone_number = Auth::user()->account->phone_number;

        }
        else {

            $this->validate($request,[
                "phone_number" => "required|min:10|max:11",
            ]);

            $phone_number = $request->input("phone_number");
        }

        if($amount < $min_purchase){

            return redirect()->back()->with([
                "error" => "Can not purchase airtime less than &#8358;". $min_purchase . ". Transaction declined."
            ]);
        }

        DB::beginTransaction();

        try {

            $check_balance = Account::where("user_email", Auth::user()->email)
                ->where("account_balance", ">=", $amount)->lockForUpdate()
                ->first();

            if (!$check_balance) {

                return redirect()->back()->with([
                    "error" => "Insufficient Funds!, Transaction declined."
                ]);
            }

            //Debit from account
            $check_balance->account_balance = $check_balance->account_balance - $amount;
            $check_balance->save();

            //Add debit to transactions
            Transaction::create([
                "transaction_type" => "Debit",
                "transaction_description" => "@Airtime Purchase / Debit / @" . $phone_number,
                "transaction_amount" => $amount,
                "account_id" => Auth::user()->account->id
            ]);

            //Send airtime using some api

            //Explicitly commit transaction
            DB::commit();

            return redirect()->back()->with([
                "success" => "Transaction Successful!."
            ]);

        } catch (\Exception $e){

            DB::rollBack();

            throw $e;
        }

    }
}
