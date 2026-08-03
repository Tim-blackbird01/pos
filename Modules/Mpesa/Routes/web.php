<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'SetSessionData', 'auth', 'language', 'timezone', 'AdminSidebarMenu'], 'prefix' => 'mpesa'], function () {

    // Settings
    Route::get('settings', [\Modules\Mpesa\Http\Controllers\SettingController::class, 'index'])
        ->name('mpesa.settings.index');
    Route::post('settings', [\Modules\Mpesa\Http\Controllers\SettingController::class, 'store'])
        ->name('mpesa.settings.store');
    Route::post('settings/register-c2b-urls', [\Modules\Mpesa\Http\Controllers\SettingController::class, 'registerC2BUrls'])
        ->name('mpesa.settings.register_c2b');

    // Transactions list
    Route::get('transactions', [\Modules\Mpesa\Http\Controllers\MpesaController::class, 'transactions'])
        ->name('mpesa.transactions.index');

    // AJAX: trigger STK push from the POS sell screen
    Route::post('stk-push', [\Modules\Mpesa\Http\Controllers\MpesaController::class, 'stkPush'])
        ->name('mpesa.stk_push');

    // AJAX: poll for payment status from the POS sell screen
    Route::post('check-status', [\Modules\Mpesa\Http\Controllers\MpesaController::class, 'checkStatus'])
        ->name('mpesa.check_status');

    // AJAX: fetch recent unlinked M-Pesa payments for the POS screen
    Route::get('recent-payments', [\Modules\Mpesa\Http\Controllers\MpesaController::class, 'recentPayments'])
        ->name('mpesa.recent_payments');

    // AJAX: link a recent M-Pesa payment to the current sale
    Route::post('link-payment', [\Modules\Mpesa\Http\Controllers\MpesaController::class, 'linkPayment'])
        ->name('mpesa.link_payment');

    // Install / update / uninstall
    Route::get('install', [\Modules\Mpesa\Http\Controllers\InstallController::class, 'index'])
        ->name('mpesa.install.index');
    Route::post('install', [\Modules\Mpesa\Http\Controllers\InstallController::class, 'install'])
        ->name('mpesa.install.run');
    Route::get('install/update', [\Modules\Mpesa\Http\Controllers\InstallController::class, 'update'])
        ->name('mpesa.install.update');
    Route::get('install/uninstall', [\Modules\Mpesa\Http\Controllers\InstallController::class, 'uninstall'])
        ->name('mpesa.install.uninstall');
});