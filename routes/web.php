<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenController;
use App\Http\Controllers\EmployeeController;

use App\Http\Middleware\OAuth;
use App\Http\Middleware\NoCacheHeaders;
use App\Http\Middleware\PublicRoute;
use App\Models\Employee;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// View Routes
Route::middleware([PublicRoute::class])->group(function () {
  Route::view('/', 'index')->name('home');
  Route::view('/login', 'login')->name('login');
  Route::view('/register', 'register')->name('register');
  Route::view('/recovery', 'recovery')->name('recovery');
  Route::view('/recovery/reset/{token}', 'recovery-reset')->name('recovery.reset');
});

// Protected Routes
Route::middleware([OAuth::class, NoCacheHeaders::class])->group(function () {
  Route::view('/dashboard', 'dashboard.index')->name('dashboard');

  Route::get('/dashboard/employee', [EmployeeController::class, 'index'])->name('dashboard.employee');
  
  
  Route::get('/dashboard/logout', [AuthenController::class, 'logout'])->name('dashboard.logout');

  // Route::post('/employee/getAllEmployee', [EmployeeController::class, 'getAllEmployee'])->name('employee.getAllEmployee');
  Route::post('/employee/addEmployee', [EmployeeController::class, 'addEmployee'])->name('employee.addEmployee');
});

// Service Routes
Route::post('/authen/register', [AuthenController::class, 'register'])->name('authen.register');
Route::post('/authen/login', [AuthenController::class, 'login'])->name('authen.login');
Route::post('/authen/recovery', [AuthenController::class, 'recovery'])->name('authen.recovery');
Route::post('/authen/recovery/reset/{token}', [AuthenController::class, 'recoveryReset'])->name('authen.recovery.reset');
