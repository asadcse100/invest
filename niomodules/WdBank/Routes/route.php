<?php

use Illuminate\Support\Facades\Route;

Route::name('admin.settings.gateway.withdraw.')->middleware(['admin'])->prefix('admin/settings/gateway/withdraw-method')->group(function () {
    Route::get('/bank', 'BankSettingsController@settingsView')->name('wd-bank-transfer');
    Route::post('/bank', 'BankSettingsController@saveBankSettings')->name('wd-bank-transfer.save');
});

Route::name('user.withdraw.account.')->middleware(['user'])->prefix('withdraw/account')->group(function () {
    Route::get('/bank', 'UserAccountController@form')->name('wd-bank-transfer.form');
    Route::post('/bank', 'UserAccountController@save')->name('wd-bank-transfer.save');
    Route::get('/bank/{id}', 'UserAccountController@edit')->name('wd-bank-transfer.edit');
    Route::post('/bank/{id}/update', 'UserAccountController@update')->name('wd-bank-transfer.update');
    Route::post('/bank/{id}/delete', 'UserAccountController@delete')->name('wd-bank-transfer.delete');
});
