<p align="center"><a href="https://restfulcountries.com" target="_blank"><img src="https://restfulcountries.com/assets/images/larabank/login.png" width="300"></a></p>

<p align="center">

<a href="https://github.com/Naterus/larabank/blob/main/LICENSE"><img src="https://restfulcountries.com/assets/images/license-mit.svg" alt="License"></a>
</p>


Larabank is a simple bank app built with laravel to illustrate real life occurrence of race conditions in software systems and how it can be handled using pessimistic lock. 

Assuming you have an account balance of 5,000 and you try to perform a transfer of 5,000 on larabank, meanwhile you have a friend using your atm card to withdraw 5,000 at the same time. at this point two separate transactions are trying to execute on one account which is known as race condition. If this is not handled, both would perform transaction successfully, leaving larabank with a loss of 5,000.

In this case, using only if statements won't help as both parties would access the current data at the same time, and the account balance check would pass for both. Therefore, any transaction that has to do with a debit should be locked to the first user that accesses the data using pessimistic lock strategy.

Race conditions occur in split seconds, and this can cause a terrible glitch in software systems. examples include double booking of plane tickets, concerts, e-commerce limited product sale and so on.

## Larabank Transfer Example
```injectablephp
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
```

The most important part of the code above is the `$check_balance` line were you chain the query with lockForUpdate(). This is where the pessimistic lock is applied on the database. Any other user trying to update that row would have to wait until the transaction commits successfully  or fails and rollback.

Mysql uses InnoDB storage engine to implement row level locks  with the `SELECT ... FOR UPDATE` statement. 

It's important to note that row level lock does not lock the whole table but only locks a particular row until transaction is completed. 
## Screen

`Dashboard`
<p><img src="https://restfulcountries.com/assets/images/larabank/dashboard.png" width="320"></p>

Since functionality is the key feature of larabank, a very basic bootstrap design was used for the user interface. Run the application using the instructions below to see other screens and how it works.

## Running Locally
Larabank is built with Laravel Framework 8.18.1 which would require [PHP](https://php.net) 7.4+ to run smoothly.

1. Open your terminal and `cd` to any directory of your choice
    ```bash
    cd your-directory
   ```
2. Clone this repository
    ```bash
    git clone https://github.com/Naterus/larabank.git
    ```
3. `cd` into the folder created for cloned project:
    ```bash
    cd larabank
   ```

4. Install packages with composer (Make sure composer is installed already)
    ```bash
    composer install
   ```

5. Make a copy of .env.example as .env
    ```bash
    cp .env.example .env
   ```

6. Generate app key
    ```bash
    php artisan key:generate
   ```

7. Create an empty database and add the database credentials to `.env` file
    ```angular2html
        DB_HOST=localhost
        DB_DATABASE=your_database_name
        DB_USERNAME=root
        DB_PASSWORD=your_password
   ```

8. Run migration
   ```bash
   php artisan migrate
   ```
9. Start laravel local server
   ```bash
    php artisan serve
    ```

10. open http://127.0.0.1:8000/ in your browser, You should see larabank login page.

11. Create new account and login. You should create two separate accounts to enable you transfer funds from one account to another.
## Todo
Project based learning is very effective in learning software development, if you are looking for a project to improve your skill regardless the technology, you can use this project as a starting point and improve on it. Here are some few things i feel can be added to make it awesome.
- Implement two-factor authentication to enhance security
- Implement API - create api endpoints for mobile app to consume.
- Implement Cron job - Run a cron job to debit users maybe every month for sms alerts, You can also implement a job to send monthly account statement to users email.
- Implement Password reset
- Implement Account Activation on sign up.
- Add beneficiaries - Enable bank user manage beneficiaries and only transfer to existing bank accounts.
- Multiple account management - allow users create and manage multiple bank accounts using their email address, I created a roadmap for that already by separating the user model from the account model. they sign up with email and then create and manage different bank accounts when logged in. This is quite advanced though as you are looking at building a SaaS like banking app and would require advanced security.
- Loans - allow users request for loans, and they pay back with interest.
- Withdrawal - Simulate atm withdrawal
- Deposit - Simulate bank deposit
- Pension - create pension plan for people with current accounts
- Implement admin portal - Enable administrators perform operations like approve loans , pension , deposits , etc based on roles. 
- Account type restriction - restrict account operations based on account type.

And many more.

## License

Licensed under the [MIT license](https://opensource.org/licenses/MIT).
