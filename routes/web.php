<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\AuthenController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\CatController;
use App\Http\Controllers\RentController;
use App\Http\Controllers\PayReceiptController;

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
  Route::get('/', [ViewController::class, 'home'])->name('home');
  Route::get('/login', [ViewController::class, 'login'])->name('login');
  Route::get('/register', [ViewController::class, 'register'])->name('register');
  Route::get('/recovery', [ViewController::class, 'recovery'])->name('recovery');
  Route::get('/recovery/reset/{token}', [ViewController::class, 'recoveryReset'])->name('recovery.reset');
  Route::get('/room', [ViewController::class, 'room'])->name('room');
  Route::get('/price', [ViewController::class, 'price'])->name('price');
  Route::get('/service', [ViewController::class, 'service'])->name('service');
  Route::get('/rule', [ViewController::class, 'rule'])->name('rule');
  Route::get('/contact', [ViewController::class, 'contact'])->name('contact');
});

// Protected Routes
Route::middleware([OAuth::class, NoCacheHeaders::class])->group(function () {
  Route::get('/dashboard', [ViewController::class, 'dashboard'])->name('dashboard');
  Route::get('/dashboard/employee', [ViewController::class, 'dashboardEmployee'])->name('dashboard.employee');
  Route::get('/dashboard/member', [ViewController::class, 'dashboardMember'])->name('dashboard.member');
  Route::get('/dashboard/cat', [ViewController::class, 'dashboardCat'])->name('dashboard.cat');
  Route::get('/dashboard/room', [ViewController::class, 'dashboardRoom'])->name('dashboard.room');
  Route::get('/dashboard/book/manage', [ViewController::class, 'dashboardBookManage'])->name('dashboard.book.manage');
  
  Route::get('/dashboard/book', [ViewController::class, 'dashboardBook'])->name('dashboard.book');
  Route::get('/dashboard/book/history', [ViewController::class, 'dashboardBookHistory'])->name('dashboard.book.history');

  Route::get('/dashboard/book/service', [ViewController::class, 'dashboardService'])->name('dashboard.book.service');

  
  Route::get('/dashboard/profile', [ViewController::class, 'dashboardProfile'])->name('dashboard.profile');
  Route::get('/dashboard/logout', [AuthenController::class, 'logout'])->name('dashboard.logout');
});

// Service Routes
Route::post('/authen/register', [AuthenController::class, 'register'])->name('authen.register');
Route::post('/authen/login', [AuthenController::class, 'login'])->name('authen.login');
Route::post('/authen/recovery', [AuthenController::class, 'recovery'])->name('authen.recovery');
Route::post('/authen/recovery/reset/{token}', [AuthenController::class, 'recoveryReset'])->name('authen.recovery.reset');

Route::get('/api/user/profile/{type}/{id}', [UserController::class, 'getProfile']);
Route::post('/api/user/profile/{type}/{id}', [UserController::class, 'updateProfile']);

Route::get('/api/employee/list', [EmployeeController::class, 'getAllEmployee']);
Route::post('/api/employee', [EmployeeController::class, 'addEmployee']);
Route::put('/api/employee/{id}', [EmployeeController::class, 'updateEmployee']);
Route::delete('/api/employee/{id}', [EmployeeController::class, 'deleteEmployee']);

Route::get('/api/member/list', [MemberController::class, 'getAllMember']);
Route::post('/api/member', [MemberController::class, 'addMember']);
Route::put('/api/member/{id}', [MemberController::class, 'updateMember']);
Route::delete('/api/member/{id}', [MemberController::class, 'deleteMember']);

Route::get('/api/room/list', [RoomController::class, 'getAllRoom']);
Route::post('/api/room', [RoomController::class, 'addRoom']);
Route::put('/api/room/{id}', [RoomController::class, 'updateRoom']);
Route::delete('/api/room/{id}', [RoomController::class, 'deleteRoom']);

Route::get('/api/cat/list', [CatController::class, 'getAllCat']);
Route::post('/api/cat', [CatController::class, 'addCat']);
Route::put('/api/cat/{id}', [CatController::class, 'updateCat']);
Route::delete('/api/cat/{id}', [CatController::class, 'deleteCat']);

Route::get('/api/rent/list', [RentController::class, 'getAllRent']);
Route::get('/api/rent/member/{id}', [RentController::class, 'getRentByMember']);
Route::post('/api/rent', [RentController::class, 'addRent']);
Route::put('/api/rent/{id}', [RentController::class, 'updateRent']);
Route::delete('/api/rent/{id}', [RentController::class, 'deleteRent']);


Route::get('/api/pay-receipt/{id}', [PayReceiptController::class, 'getPayReceiptByRent']);

// Route::put('/api/cat/{id}', [RentController::class, 'updateCat']);