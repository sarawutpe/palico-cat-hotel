<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\AuthenController;
use App\Http\Middleware\OAuth;
use Illuminate\Support\Facades\Session;

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

// View Routes
Route::view('/', 'index')->name('home');
Route::view('/login', 'login')->name('login');
Route::view('/register', 'register')->name('register');
Route::view('/recovery', 'recovery')->name('recovery');
Route::view('/recovery/reset/{token}', 'recovery-reset')->name('recovery-reset');

Route::middleware([OAuth::class])->group(function () {
  Route::view('/dashboard', 'dashboard.index')->name('dashboard');
  Route::get('/dashboard/logout', [MemberController::class, 'logout'])->name('dashboard.logout');
});

// Service Routes
Route::post('/authen/register', [AuthenController::class, 'register'])->name('authen.register');
Route::post('/authen/login', [AuthenController::class, 'login'])->name('authen.login');
Route::get('/authen/logout', [AuthenController::class, 'logout'])->name('authen.logout');
Route::post('/authen/recovery', [AuthenController::class, 'recovery'])->name('authen.recovery');
Route::post('/authen/recovery/reset/{token}', [AuthenController::class, 'recoveryReset'])->name('authen.recovery.reset');
