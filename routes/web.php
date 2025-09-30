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
    Route::post('/dashboard/mark-watched', [Controllers\DashboardController::class, 'markVideoAsWatched'])->name('dashboard.mark-watched');

    Route::get('videos/watch', [Controllers\VideoController::class, 'watch'])->name('videos.watch');
    Route::post('videos/mark-watched', [Controllers\VideoController::class, 'markAsWatched'])->name('videos.mark-watched');
    
    Route::get('/wallet', [Controllers\UserAccountController::class, 'wallet'])->name('wallet.index');
    
    // Profile Routes
    Route::get('/profile', [Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Support Routes
    Route::get('/support', [Controllers\SupportController::class, 'index'])->name('support.index');
    Route::post('/support', [Controllers\SupportController::class, 'store'])->name('support.store');
    Route::get('/support/history', [Controllers\SupportController::class, 'history'])->name('support.history');
    Route::get('/support/{id}', [Controllers\SupportController::class, 'show'])->name('support.show');
    Route::get('/support/{id}/response', [Controllers\SupportController::class, 'viewResponse'])->name('support.response');
    
    // Admin Support Routes
    Route::get('/admin/support', [Controllers\SupportController::class, 'adminIndex'])->name('admin.support.index');
    Route::get('/admin/support/{id}', [Controllers\SupportController::class, 'adminShow'])->name('admin.support.show');
    Route::post('/admin/support/{id}/respond', [Controllers\SupportController::class, 'adminRespond'])->name('admin.support.respond');
    Route::post('/admin/support/{id}/update-status', [Controllers\SupportController::class, 'adminUpdateStatus'])->name('admin.support.update-status');
    Route::post('/admin/support/{id}/close', [Controllers\SupportController::class, 'adminClose'])->name('admin.support.close');
    
    Route::get('/approveUser/{id}', [Controllers\UserController::class, 'approveUser'])->name('approveUser');
    Route::post('/withdrawToAccount', [Controllers\WithdrawController::class, 'withdrawToAccount'])->name('withdrawToAccount');
    Route::get('/adjustUserAccount/{id}', [Controllers\SuperadminController::class, 'adjustUserAccount'])->name('adjustUserAccount');
    
    Route::resource('users', Controllers\UserController::class)->withTrashed();
    Route::get('users/export', [Controllers\UserController::class, 'export'])->name('users.export');
    Route::resource('plans', Controllers\InvestmentPlanController::class)->withTrashed();
    Route::resource('bankAccounts', Controllers\BankAccountController::class)->withTrashed();
    Route::resource('deposits', Controllers\DepositController::class)->withTrashed();
    Route::resource('withdraws', Controllers\WithdrawController::class)->withTrashed();
    Route::get('withdraws/export', [Controllers\WithdrawController::class, 'export'])->name('withdraws.export');
    Route::post('withdraws/bulk-approve', [Controllers\WithdrawController::class, 'bulkApprove'])->name('withdraws.bulk-approve');
    Route::post('withdraws/bulk-reject', [Controllers\WithdrawController::class, 'bulkReject'])->name('withdraws.bulk-reject');
    Route::resource('userAccounts', Controllers\UserAccountController::class)->withTrashed();
    Route::resource('userSubscriptions', Controllers\UserSubscriptionController::class)->withTrashed();
    Route::resource('videos', Controllers\VideoController::class);
    
    // Admin Settings Routes
    Route::get('admin/settings', [Controllers\AdminSettingsController::class, 'index'])->name('admin.settings');
    Route::post('admin/settings/general', [Controllers\AdminSettingsController::class, 'updateGeneral'])->name('admin.settings.general');
    Route::post('admin/settings/payment', [Controllers\AdminSettingsController::class, 'updatePayment'])->name('admin.settings.payment');
    Route::post('admin/settings/notifications', [Controllers\AdminSettingsController::class, 'updateNotifications'])->name('admin.settings.notifications');
    Route::post('admin/settings/announcement', [Controllers\AdminSettingsController::class, 'sendAnnouncement'])->name('admin.settings.announcement');
    Route::post('admin/settings/clear-cache', [Controllers\AdminSettingsController::class, 'clearCache'])->name('admin.settings.clear-cache');
    
    // Notification Routes
    Route::get('admin/notifications', [Controllers\NotificationController::class, 'index'])->name('admin.notifications');
    Route::post('admin/notifications/send', [Controllers\NotificationController::class, 'send'])->name('admin.notifications.send');
    Route::post('admin/notifications/mark-read', [Controllers\NotificationController::class, 'markAsRead'])->name('admin.notifications.mark-read');
    Route::delete('admin/notifications/{id}', [Controllers\NotificationController::class, 'destroy'])->name('admin.notifications.destroy');
    Route::get('user/notifications', [Controllers\NotificationController::class, 'getUserNotifications'])->name('user.notifications');

    // Lucky Withdraw Routes
    Route::get('/lucky-withdraw', [Controllers\LuckyWithdrawController::class, 'index'])->name('lucky-withdraw.index');
    Route::get('/lucky-withdraw/create', [Controllers\LuckyWithdrawController::class, 'create'])->name('lucky-withdraw.create');
    Route::post('/lucky-withdraw', [Controllers\LuckyWithdrawController::class, 'store'])->name('lucky-withdraw.store');
    Route::get('/lucky-withdraw/{luckyWithdrawEntry}', [Controllers\LuckyWithdrawController::class, 'show'])->name('lucky-withdraw.show');
    Route::get('/lucky-withdraw/stats', [Controllers\LuckyWithdrawController::class, 'getRoundStats'])->name('lucky-withdraw.stats');
    
    // Admin Lucky Withdraw Routes
    Route::get('/admin/lucky-withdraw', [Controllers\LuckyWithdrawController::class, 'adminIndex'])->name('admin.lucky-withdraw.index');
    Route::post('/admin/lucky-withdraw/{luckyWithdrawEntry}/approve', [Controllers\LuckyWithdrawController::class, 'approve'])->name('admin.lucky-withdraw.approve');
    Route::post('/admin/lucky-withdraw/{luckyWithdrawEntry}/reject', [Controllers\LuckyWithdrawController::class, 'reject'])->name('admin.lucky-withdraw.reject');
    Route::post('/admin/lucky-withdraw/select-winner', [Controllers\LuckyWithdrawController::class, 'selectWinner'])->name('admin.lucky-withdraw.select-winner');

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
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    return "Cache is cleared";
});

// // 404 for undefined routes
// Route::any('/{page?}', function () {
//     return View::make('pages.error-pages.error-404');
// })->where('page', '.*');
