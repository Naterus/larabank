<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/",[HomeController::class,'index'])->name("home");

Route::get("/register",[HomeController::class,'register'])->name("register");

Route::post("/register/create-account",[AuthController::class,'createAccount'])->name("register.submit");

Route::get("/account/dashboard",[AccountController::class,'dashboard'])->name("account.dashboard");

Route::get("/account/transfer-funds",[AccountController::class,'transferFunds'])->name("account.transfer");

Route::post("/account/transfer-funds/submit",[AccountController::class,'submitTransfer'])->name("account.transfer.submit");
