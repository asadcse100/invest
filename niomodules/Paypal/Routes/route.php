<?php

use Illuminate\Support\Facades\Route;

Route::name('admin.settings.gateway.payment.')->middleware(['web', 'auth', 'admin'])->prefix('admin/settings/gateway/payment-method')->group(function () {
    Route::get('/paypal', 'PaypalSettingsController@settingsView')->name('paypal');
    Route::post('/paypal', 'PaypalSettingsController@savePaypalSettings')->name('paypal.save');
});

Route::name('user.gateway.make-payment.')->middleware(['web', 'auth', 'user'])->prefix('user/gateway/payment')->group(function(){
    Route::post('/paypal', 'TransactionConfirmationController@makePayment')->name('paypal');
});

Route::name('public.')->prefix('public/gateway')->group(function(){
    Route::get('/payment/paypal/cancel', 'TransactionConfirmationController@cancelPaypal')->name('payment.paypal.cancel');
    Route::get('/payment/paypal/return', 'TransactionConfirmationController@returnPaypal')->name('payment.paypal.return');
});

