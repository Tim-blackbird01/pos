<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public callback routes
|--------------------------------------------------------------------------
| Safaricom rejects any callback URL containing the word "mpesa", so these
| routes use a neutral prefix: /payment/gateway/...
|
| - stk/{business_id} : STK Push (Lipa Na M-Pesa Online) result callback
| - c2b-confirm       : Direct Paybill payment confirmation
| - c2b-validate      : Pre-payment validation (we accept everything)
*/

Route::group(['prefix' => 'payment/gateway'], function () {

    Route::post('stk/{business_id?}', [\Modules\Mpesa\Http\Controllers\CallbackController::class, 'stkCallback'])
        ->name('mpesa.callback.stk');

    Route::post('c2b-confirm', [\Modules\Mpesa\Http\Controllers\CallbackController::class, 'c2bConfirmation'])
        ->name('mpesa.callback.c2b_confirmation');

    Route::post('c2b-validate', [\Modules\Mpesa\Http\Controllers\CallbackController::class, 'c2bValidation'])
        ->name('mpesa.callback.c2b_validation');
});
