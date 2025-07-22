<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

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

Route::get('/test', [Controllers\TestController::class, 'bonusRecalculate'])->name('test');
Route::get('/profitCalculation', [Controllers\UserSubscriptionController::class, 'profitCalculation'])->name('profitCalculation');

Route::get('/', [Controllers\FrontEndController::class, 'index'])->name('home');

Route::middleware(['auth','verified'])->group(function () {
    Route::get('/dashboard', [Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', Controllers\UserController::class)->withTrashed();
    Route::resource('plans', Controllers\InvestmentPlanController::class)->withTrashed();
    Route::resource('bankAccounts', Controllers\BankAccountController::class)->withTrashed();
    Route::resource('deposits', Controllers\DepositController::class)->withTrashed();
    Route::resource('withdraws', Controllers\WithdrawController::class)->withTrashed();
    Route::resource('userAccounts', Controllers\UserAccountController::class)->withTrashed();
    Route::resource('userSubscriptions', Controllers\UserSubscriptionController::class)->withTrashed();

    Route::get('/approveUser/{id}', [Controllers\UserController::class, 'approveUser'])->name('approveUser');
    Route::post('/withdrawToAccount', [Controllers\WithdrawController::class, 'withdrawToAccount'])->name('withdrawToAccount');
    Route::get('/adjustUserAccount/{id}', [Controllers\SuperadminController::class, 'adjustUserAccount'])->name('adjustUserAccount');
});


require __DIR__ . '/auth.php';


/*
============================================================================
======================= Theme routes below =================================
============================================================================
*/
Route::get('/theme', function () {
    return view('dashboard');
});

// Route::get('/','DashboardController@index');

// For Clear cache
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

// 404 for undefined routes
Route::any('/{page?}', function () {
    return View::make('pages.error-pages.error-404');
})->where('page', '.*');
