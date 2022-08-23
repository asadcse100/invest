<?php

use Illuminate\Support\Facades\Route;

Route::name('admin.settings.gateway.withdraw.')->middleware(['admin'])->prefix('admin/settings/gateway/withdraw-method')->group(function () {
    Route::get('/paypal', 'WdPaypalSettingsController@settingsView')->name('wd-paypal');
    Route::post('/paypal', 'WdPaypalSettingsController@savePaypalSettings')->name('wd-paypal.save');
});

Route::name('user.withdraw.account.')->middleware(['user'])->prefix('withdraw/account')->group(function () {
    Route::get('/paypal', 'UserAccountController@form')->name('wd-paypal.form');
    Route::post('/paypal', 'UserAccountController@save')->name('wd-paypal.save');
    Route::get('/paypal/{id}', 'UserAccountController@edit')->name('wd-paypal.edit');
    Route::post('/paypal/{id}/update', 'UserAccountController@update')->name('wd-paypal.update');
    Route::post('/paypal/{id}/delete', 'UserAccountController@delete')->name('wd-paypal.delete');
});

