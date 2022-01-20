<?php

use App\Http\Controllers\BreakHourController;
use App\Http\Controllers\CashAdvanceController;
use App\Http\Controllers\CashAdvanceDeductionController;
use App\Http\Controllers\CashDeductionController;
use App\Http\Controllers\ContributionController;
use App\Http\Controllers\DailyPayrollExportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\EmployeeTypeController;
use App\Http\Controllers\ExpensesByDepartmentController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TimelogsController;

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
})->name('login');

Route::resource('/users',UserController::class)->middleware('auth');
Route::resource('/employees',EmployeesController::class)->middleware('auth');
Route::resource('/employeeTypes',EmployeeTypeController::class)->middleware('auth');
Route::resource('/position',PositionController::class)->middleware('auth');
Route::resource('/location',LocationController::class)->middleware('auth');
Route::resource('/cashadvancedescriptions',CashAdvanceController::class)->middleware('auth');
Route::resource('/breakhours',BreakHourController::class)->middleware('auth');
Route::resource('/timelogs',TimelogsController::class)->middleware('auth');
Route::resource('/cashadvancededuction',CashAdvanceDeductionController::class)->middleware('auth');
Route::resource('/cashdeductions',CashDeductionController::class)->middleware('auth');
Route::resource('/contribution',ContributionController::class)->middleware('auth');
Route::resource('/reports',ReportController::class)->middleware('auth');
Route::resource('/expenses',ExpensesByDepartmentController::class)->middleware('auth');
Route::post('/authenticate', [UserController::class, 'authenticate'])->name('authenticate');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function() {
    Route::get('/cashcontribution', function () {
        return view('components.home');
    });
    Route::get('/cashdeduction', function () {
        return view('components.home');
    });
    Route::get('/cashadvance', function () {
        return view('components.home');
    });
    Route::get('/payroll', function () {
        return view('components.home');
    });
    Route::get('/settings', function () {
        return view('components.home');
    });
    Route::get('/report', function () {
        return view('components.home');
    });
    Route::get('/expense', function () {
        return view('components.home');
    });
});

Route::post('/dailypayrollexport', [DailyPayrollExportController::class, 'generateDailyPayroll'])->middleware('auth');
Route::post('/departmentpay', [DailyPayrollExportController::class, 'generateDepartmentTotalPay'])->middleware('auth');
Route::post('/departmentexpenses', [DailyPayrollExportController::class, 'generateDepartmentExpenses'])->middleware('auth');
Route::post('/dailyprocessing', [DailyPayrollExportController::class, 'generateProcessingLogBook'])->middleware('auth');
Route::post('/weeklypayroll', [DailyPayrollExportController::class, 'generateWeeklyPayrollDepartment'])->middleware('auth');
Route::post('/employeerecordhistory', [DailyPayrollExportController::class, 'generateEmployeeRecordHistory'])->middleware('auth');
Route::get('/generatepayslip/{id}', [ReportController::class, 'generatePaySlip'])->middleware('auth');

Route::get('/testers' , function() {
    return view('components.test');
});
