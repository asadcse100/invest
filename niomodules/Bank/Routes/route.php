<?php

use Illuminate\Support\Facades\Route;

Route::name('admin.settings.gateway.payment.')->middleware(['admin'])->prefix('admin/settings/gateway/payment-method')->group(function () {
    Route::get('/bank-transfer', 'BankSettingsController@settingsView')->name('bank-transfer');
    Route::post('/bank-transfer', 'BankSettingsController@saveBankSettings')->name('bank-transfer.save');
});

Route::middleware(['user'])->group(function(){
    Route::get('deposit-complete/bank-transfer', 'TransactionConfirmationController@depositComplete')->name('user.bank.deposit.complete');
});
