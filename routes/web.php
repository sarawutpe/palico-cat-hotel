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
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceListController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\CheckinCatController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;

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
  Route::get('/dashboard/product', [ViewController::class, 'dashboardProduct'])->name('dashboard.product');
  Route::get('/dashboard/report', [ViewController::class, 'dashboardReport'])->name('dashboard.report');
  Route::get('/dashboard/profile', [ViewController::class, 'dashboardProfile'])->name('dashboard.profile');
  Route::get('/dashboard/logout', [AuthenController::class, 'logout'])->name('dashboard.logout');
});


// Service Routes
Route::post('/authen/register', [AuthenController::class, 'register'])->name('authen.register');
Route::post('/authen/login', [AuthenController::class, 'login'])->name('authen.login');
Route::post('/authen/recovery', [AuthenController::class, 'recovery'])->name('authen.recovery');
Route::post('/authen/recovery/reset/{token}', [AuthenController::class, 'recoveryReset'])->name('authen.recovery.reset');

// User Routes
Route::get('/api/user/profile/{type}/{id}', [UserController::class, 'getProfile']);
Route::post('/api/user/profile/{type}/{id}', [UserController::class, 'updateProfile']);

// Employee Routes
Route::get('/api/employee/list', [EmployeeController::class, 'getAllEmployee']);
Route::post('/api/employee', [EmployeeController::class, 'addEmployee']);
Route::put('/api/employee/{id}', [EmployeeController::class, 'updateEmployee']);
Route::delete('/api/employee/{id}', [EmployeeController::class, 'deleteEmployee']);

// Member Routes
Route::get('/api/member/list', [MemberController::class, 'getAllMember']);
Route::post('/api/member', [MemberController::class, 'addMember']);
Route::put('/api/member/{id}', [MemberController::class, 'updateMember']);
Route::delete('/api/member/{id}', [MemberController::class, 'deleteMember']);

// Room Routes
Route::get('/api/room/list', [RoomController::class, 'getAllRoom']);
Route::post('/api/room', [RoomController::class, 'addRoom']);
Route::put('/api/room/{id}', [RoomController::class, 'updateRoom']);
Route::delete('/api/room/{id}', [RoomController::class, 'deleteRoom']);

// Cat Routes
Route::get('/api/cat/list', [CatController::class, 'getAllCat']);
Route::get('/api/cat/member/{id}', [CatController::class, 'getCatByMember']);
Route::post('/api/cat', [CatController::class, 'addCat']);
Route::put('/api/cat/{id}', [CatController::class, 'updateCat']);
Route::delete('/api/cat/{id}', [CatController::class, 'deleteCat']);

// Rent Routes
Route::get('/api/rent/list', [RentController::class, 'getAllRent']);
Route::get('/api/rent/list/member/{id}', [RentController::class, 'getAllRentByMember']);
Route::get('/api/rent/{id}', [RentController::class, 'getRentById']);
Route::get('/api/rent/member/{id}', [RentController::class, 'getRentByMember']);
Route::post('/api/rent', [RentController::class, 'addRent']);
Route::put('/api/rent/{id}', [RentController::class, 'updateRent']);
Route::delete('/api/rent/{id}', [RentController::class, 'deleteRent']);

// Pay Receipt Routes
Route::get('/api/pay-receipt/{id}', [PayReceiptController::class, 'getPayReceiptByRent']);

// Service Routes
Route::get('/api/service/list', [ServiceController::class, 'getAllService']);
Route::post('/api/service', [ServiceController::class, 'addService']);

// Service List Routes
Route::get('/api/service-list/rent/{id}', [ServiceListController::class, 'getAllServiceListByService']);
Route::post('/api/service-list', [ServiceListController::class, 'addServiceList']);
Route::put('/api/service-list/{id}', [ServiceListController::class, 'updateServiceList']);
Route::delete('/api/service-list/{id}', [ServiceListController::class, 'deleteServiceList']);

// Checkin Routes
Route::post('/api/checkin', [CheckinController::class, 'addCheckin']);
Route::put('/api/checkin/{id}', [CheckinController::class, 'updateCheckin']);

// Checkin Cat Routes
Route::post('/api/checkin-cat', [CheckinCatController::class, 'addCheckinCat']);
Route::put('/api/checkin-cat/{id}', [CheckinCatController::class, 'updateCheckinCat']);

// Product Routes
Route::get('/api/product/list', [ProductController::class, 'getAllProduct']);
Route::post('/api/product', [ProductController::class, 'addProduct']);
Route::put('/api/product/{id}', [ProductController::class, 'updateProduct']);
Route::delete('/api/product/{id}', [ProductController::class, 'deleteProduct']);

// Report Routes
Route::get('/api/report/stats', [ReportController::class, 'getAllStats']);
Route::get('/api/report/income', [ReportController::class, 'getAllIncome']);