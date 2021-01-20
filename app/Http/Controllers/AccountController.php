<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;

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
            "amount" => "required"
        ]);

        $amount = $request->input("amount");
        $account_number = $request->input("account_number");
        $pattern =  substr($account_number,0,2);

        if($pattern != 71){
            return redirect()->back()->with([
                "error" => "Invalid account number. please enter a valid 12 digit account number starting with 71."
            ]);
        }

        DB::beginTransaction();

        try {

            //Initiate exclusive lock for first user to access this record
            $check_balance = Account::where("user_email", Auth::user()->email)
                ->where("account_balance", ">", $amount)->lockForUpdate()
                ->first();

            if (!$check_balance) {

                return redirect()->back()->with([
                    "error" => "Insufficient Funds!, Transaction declined."
                ]);
            }

            //sleep(40); //delay script to test lock on incognito tab

            //Debit from account
            $check_balance->account_balance = $check_balance->account_balance - $amount;
            $check_balance->save();

            //Add debit to transactions
            Transaction::create([
                "transaction_type" => "Debit",
                "transaction_description" => "@Transfer / Debit / @" . $account_number,
                "transaction_amount" => $amount,
                "account_id" => Auth::user()->account->id
            ]);

            //Credit Beneficiary account as well.

            DB::commit();

            return redirect()->back()->with([
                "success" => "Transaction Successful!."
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
