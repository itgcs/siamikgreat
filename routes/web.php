<?php

use App\Http\Controllers\Admin\{
   AdminController,
   DashboardController,
   RegisterController,
    StudentController,
};
use App\Http\Controllers\SuperAdmin\{
   SuperAdminController
};
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Livewire\Counter;

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
Route::get('/counter', Counter::class);

Route::middleware(['admin'])->prefix('/admin')->group(function () {
   
   Route::prefix('/dashboard')->group(function () {
      Route::get('/', [AdminController::class, 'index']);
   });

   Route::prefix('/register')->group(function () {
      Route::get('/', [RegisterController::class, 'index']);
      Route::post('/post', [RegisterController::class, 'register'])->name('actionRegister');
   });

   Route::prefix('/list')->group(function () {
      Route::get('/', [StudentController::class, 'index']);
   });

   Route::prefix('/detail')->group(function () {
      Route::get('/{id}', [StudentController::class, 'detail']);
   });
   
   Route::prefix('/update')->group(function () {
      Route::put('/{id}', [StudentController::class, 'actionEdit'])->name('student.update');
      Route::get('/{id}', [StudentController::class, 'edit']);
   });
});

Route::middleware(['check.superadmin'])->prefix('superadmin')->group(function () {
   Route::prefix('/dashboard')->group(function () {
      Route::get('/', [AdminController::class, 'index']);
   });

   Route::prefix('/register')->group(function () {
      Route::get('/', [RegisterController::class, 'index']);
   });

   
});