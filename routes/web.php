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
Route::view('/forgot-password', 'forgot-password')->name('forgot-password');

// Route::view('/reset-password/{token?}', 'reset-password');
Route::get('/reset-password/{token?}', [AuthenController::class, 'resetPassword'])->name('reset-password');

// Route::get('/reset-password', [AuthenController::class, 'resetPassword'])->name('reset-password');

Route::middleware([OAuth::class])->group(function () {
  Route::view('/dashboard', 'dashboard.index')->name('dashboard');
  Route::get('/dashboard/logout', [MemberController::class, 'logout'])->name('dashboard.logout');
});

// Service Routes
Route::post('/authen/register', [AuthenController::class, 'register'])->name('authen.register');
Route::post('/authen/login', [AuthenController::class, 'login'])->name('authen.login');
Route::get('/authen/logout', [AuthenController::class, 'logout'])->name('authen.logout');


Route::post('/authen/forgot-password', [AuthenController::class, 'forgotPassword']);
