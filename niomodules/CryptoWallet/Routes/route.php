<?php

use Illuminate\Support\Facades\Route;

Route::name('admin.settings.gateway.payment.')->middleware(['auth', 'admin'])->prefix('admin/settings/gateway/payment-method')->group(function () {
    Route::get('/crypto-wallet', 'WalletSettingsController@settingsView')->name('crypto-wallet');
    Route::post('/crypto-wallet', 'WalletSettingsController@saveWalletSettings')->name('crypto-wallet.save');
});

Route::middleware(['user'])->group(function(){
    Route::get('crypto-wallet/deposit-complete', 'TransactionConfirmationController@depositComplete')->name('user.crypto.wallet.deposit.complete');
    Route::post('crypto-wallet/deposit/reference', 'TransactionConfirmationController@saveReference')->name('user.crypto.wallet.deposit.reference');
});

