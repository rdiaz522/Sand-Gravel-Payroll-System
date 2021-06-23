<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\EmployeeTypeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PositionController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::resource('/users',UserController::class)->middleware('auth');
Route::resource('/employees',EmployeesController::class)->middleware('auth');
Route::resource('/employeeTypes',EmployeeTypeController::class)->middleware('auth');
Route::resource('/position',PositionController::class)->middleware('auth');
Route::resource('/location',LocationController::class)->middleware('auth');
Route::post('/authenticate', [UserController::class, 'authenticate'])->name('authenticate');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/payroll', function () {
    return view('components.home');
});
