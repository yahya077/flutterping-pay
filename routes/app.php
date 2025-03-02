<?php

use yahya077\FlutterpingPay\Http\Controllers\PaymentController;

\Illuminate\Support\Facades\Route::group([
    'domain' => config('flutterping-pay.route.domain', null),
    'prefix' => config('flutterping-pay.route.prefix', 'resource/payment'),
    'middleware' => config('flutterping-pay.route.middleware', ['web']),
    'as' => 'flutterping-pay.'
], function () {
    \Illuminate\Support\Facades\Route::get('/', [PaymentController::class, 'index'])->name('index');
    \Illuminate\Support\Facades\Route::post('/completePayment', [PaymentController::class, 'completePayment'])->name('completePayment');
});
