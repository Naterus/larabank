<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("user_email")->index();
            $table->string("account_name");
            $table->string("account_number",12)->unique();
            $table->string("alternate_email")->nullable();
            $table->string("phone_number")->nullable();
            $table->enum("account_type",["Savings","Current"])->default("Savings");
            $table->decimal("account_balance",10,2);
            $table->foreign("user_email")->references("email")->on("users")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
