<?php

use Illuminate\Support\Facades\Route;

Route::name('admin.investment.')->middleware(['admin'])->prefix('admin/investment')->group(function () {
    Route::get('/dashboard', 'Admin\InvestDashboardController@index')->name('dashboard');;

    Route::get('/history/{status?}', 'Admin\InvestedPlansController@investedPlanList')->name('list');
    Route::get('/plan/details/{id}', 'Admin\InvestedPlansController@showInvestmentDetails')->name('details');
    Route::get('/plan/action', 'Admin\InvestedPlansController@showInvestmentDetails')->name('plan.action');
    Route::post('plan/approve/{id?}', 'Admin\InvestedPlansController@approveInvestment')->name('plan.approve');
    Route::post('plan/cancel/{id?}', 'Admin\InvestedPlansController@cancelInvestment')->name('plan.cancel');
    Route::post('plan/complete/{id?}', 'Admin\InvestedPlansController@completeInvestment')->name('plan.complete');
    Route::get('/profits/{type?}', 'Admin\LedgerProfitsController@profitList')->name('profits.list');
    Route::get('/transactions/{type?}', 'Admin\LedgerProfitsController@transactionList')->name('transactions.list');

    Route::get('/process/plans', 'Admin\InvestedPlansController@processPlans')->name('process.plans');
    Route::post('/process/plans/sync', 'Admin\InvestedPlansController@processSyncPlans')->name('process.plans.sync');

    Route::get('/process/profits', 'Admin\LedgerProfitsController@processProfits')->name('process.profits');
    Route::post('/process/profit/payout', 'Admin\LedgerProfitsController@processPayoutProfit')->name('process.profit.payout');
    Route::post('/process/profits/payout', 'Admin\LedgerProfitsController@processPayoutProfits')->name('process.profits.payout');

    Route::get('/process/transfers', 'Admin\InvestedPlansController@processTransfers')->name('process.transfers');
    Route::post('/process/transfers/complete', 'Admin\InvestedPlansController@completeTransfers')->name('process.transfers.complete');

    Route::get('/manual/add', 'Admin\LedgerProfitsController@manualTnxAdd')->name('manual.add');
    Route::post('/manual/save', 'Admin\LedgerProfitsController@manualTnxSave')->name('manual.save');

    // Schemes
    Route::get('/schemes/{status?}', 'Admin\InvestmentSchemeController@schemeList')->name('schemes');
    Route::get('/scheme/{action?}', 'Admin\InvestmentSchemeController@actionScheme')->name('scheme.action');
    Route::post('/scheme/save', 'Admin\NewSchemeController@saveScheme')->name('scheme.save');
    Route::post('/scheme/update/{id?}', 'Admin\InvestmentSchemeController@updateScheme')->name('scheme.update');
    Route::post('/scheme/status', 'Admin\InvestmentSchemeController@updateSchemeStatus')->name('scheme.status');
});

Route::name('admin.settings.investment.')->middleware(['admin'])->prefix('admin/settings/investment')->group(function () {
    Route::get('/apps', 'Admin\SettingsController@appsSettings')->name('apps');
    Route::post('/save', 'Admin\SettingsController@saveSettings')->name('save');
});

Route::name('user.investment.')->middleware(['user'])->group(function () {
    Route::get('/investment', 'User\InvestmentController@index')->name('dashboard');
    Route::get('/investment/plans', 'User\InvestmentController@planList')->name('plans');
    Route::get('/investment/plan/{id}', 'User\InvestmentController@investmentDetails')->name('details');
    Route::get('/investment/history/{type?}', 'User\InvestmentController@investmentHistory')->name('history');
    Route::get('/investment/transactions/{type?}', 'User\InvestmentController@transactionList')->name('transactions');

    Route::get('/invest/{ucode?}', 'User\InvestController@showPlans')->name('invest');
    Route::post('/invest/preview', 'User\InvestController@previewInvest')->name('invest.preview');
    Route::post('/invest/confirm', 'User\InvestController@confirmInvest')->name('invest.confirm');
    Route::post('/invest/cancel/{id}', 'User\InvestController@cancelInvestment')->name('invest.cancel');

    Route::get('/investment/payout', 'User\InvestmentController@payoutInvest')->name('payout');
    Route::post('/investment/payout/proceed', 'User\InvestmentController@payoutProceed')->name('payout.proceed');

    Route::get('/investment/settings', 'User\InvestmentController@ivSettings')->name('settings');
    Route::post('/investment/settings/save', 'User\InvestmentController@saveIvSettings')->name('settings.save');
});
