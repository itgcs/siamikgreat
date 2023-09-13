<?php

use App\Http\Controllers\Admin\{
   AdminController,
   DashboardController,
   RegisterController,
};
use App\Http\Controllers\SuperAdmin\{
   SuperAdminController
};
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [UserController::class, 'login']);
Route::post('/login', [UserController::class, 'actionLogin'])->name('actionLogin');


Route::middleware('check.admin')->prefix('/admin')->group(function () {

   Route::prefix('/dashboard')->group(function () {
      Route::get('/', [AdminController::class, 'index']);
   });

   Route::prefix('/register')->group(function () {
      Route::get('/', [RegisterController::class, 'index']);
      Route::post('/post', [RegisterController::class, 'register'])->name('actionRegister');
   });
});

Route::middleware('check.superadmin')->prefix('superadmin')->group(function () {
   Route::prefix('/dashboard')->group(function () {
      Route::get('/', [AdminController::class, 'index']);
   });

   Route::prefix('/register')->group(function () {
      Route::get('/', [RegisterController::class, 'index']);
   });

   
});