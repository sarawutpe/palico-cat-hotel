<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\CatController;

use App\Http\Middleware\OAuth;
use App\Http\Middleware\NoCacheHeaders;
use App\Http\Middleware\PublicRoute;

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
  Route::view('/room', 'room')->name('room');
  Route::view('/price', 'price')->name('price');
  Route::view('/rule', 'rule')->name('rule');
  Route::view('/contact', 'contact')->name('contact');
});

// Protected Routes
Route::middleware([OAuth::class, NoCacheHeaders::class])->group(function () {
  Route::get('/dashboard/logout', [AuthenController::class, 'logout'])->name('dashboard.logout');

  Route::view('/dashboard', 'dashboard.index')->name('dashboard');

  Route::view('/dashboard/employee', 'dashboard.employee')->name('dashboard.employee');
  Route::view('/dashboard/cat', 'dashboard.cat')->name('dashboard.cat');
  Route::view('/dashboard/room', 'dashboard.room')->name('dashboard.room');

  // Member views
  Route::view('/dashboard/profile', 'dashboard.member.profile')->name('dashboard.member.profile');
});

Route::middleware([OAuth::class])->prefix('api')->group(function () {
  Route::get('/employee/list', [EmployeeController::class, 'getAllEmployee']);
  Route::post('/employee', [EmployeeController::class, 'addEmployee']);
  Route::put('/employee/{id}', [EmployeeController::class, 'updateEmployee']);
  Route::delete('/employee/{id}', [EmployeeController::class, 'deleteEmployee']);

  Route::get('/room/list', [RoomController::class, 'getAllRoom']);
  Route::post('/room', [RoomController::class, 'addRoom']);
  Route::put('/room/{id}', [RoomController::class, 'updateRoom']);
  Route::delete('/room/{id}', [RoomController::class, 'deleteRoom']);

  Route::get('/cat/list', [CatController::class, 'getAllCat']);
  Route::post('/cat', [CatController::class, 'addCat']);
  Route::put('/cat/{id}', [CatController::class, 'updateCat']);
  Route::delete('/cat/{id}', [CatController::class, 'deleteCat']);
});

// Service Routes
Route::post('/authen/register', [AuthenController::class, 'register'])->name('authen.register');
Route::post('/authen/login', [AuthenController::class, 'login'])->name('authen.login');
Route::post('/authen/recovery', [AuthenController::class, 'recovery'])->name('authen.recovery');
Route::post('/authen/recovery/reset/{token}', [AuthenController::class, 'recoveryReset'])->name('authen.recovery.reset');

Route::get('/api/authen/profile/{type}/{id}', [AuthenController::class, 'getProfile'])->name('authen.getProfile');
Route::post('/api/authen/profile/{type}/{id}', [AuthenController::class, 'updateProfile'])->name('authen.updateProfile');
