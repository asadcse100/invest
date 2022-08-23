<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'AdminDashboardController@index');
Route::get('dashboard', 'AdminDashboardController@index')->name('dashboard');
Route::get('system/cache', 'ApplicationSettingsController@cacheClear')->name('system.cache');
Route::get('system/status', 'ApplicationSettingsController@systemStatus')->name('systeminfo');

// Users
Route::get('users/administrator', 'UserController@administrator')->name('users.administrator');
Route::get('users/list/{state?}', 'UserController@index')->name('users');
Route::get('users/user/{id}/{type}', 'UserController@showUserDetails')->name('users.details');
Route::get('users/user/refers', 'UserController@getReferralTree')->name('users.user.refers');
Route::get('users/export/all', 'UserController@exportUsers')->name('users.export');

Route::post('users/new', 'UserController@saveUser')->name('users.save');
Route::post('users/update', 'UserController@updateAction')->name('users.action');
Route::post('users/update/bulk', 'UserController@bulkAction')->name('users.action.bulk');
Route::post('users/send-email', 'UserController@sendEmail')->name('users.send.email');

// Transactions
Route::get('deposit/{status}', 'TransactionController@allDeposit')->name('transactions.deposit.all');
Route::get('withdraw/{status}', 'TransactionController@allWithdraw')->name('transactions.withdraw.all');
Route::get('referral/{status}', 'TransactionController@allReferral')->name('transactions.referral.all');

Route::get('transactions/list/{list_type?}', 'TransactionController@index')->name('transactions.list');
Route::get('transactions/view', 'TransactionController@showDetails')->name('transaction.details');

Route::post('transactions/update/{action?}', 'TransactionController@actionUpdate')->name('transaction.update');
Route::post('transactions/check/online', 'TransactionController@checkStatus')->name('transaction.status.check');

Route::get('transactions/manual', 'TransactionController@manualTnxAdd')->name('transaction.manual.add');
Route::post('transactions/manual/{type?}', 'TransactionController@manualTnxSave')->name('transaction.manual.save');

// Settings
Route::get('setup', 'QuickSettingsController@index')->name('quick-setup');
Route::any('setup/system', 'QuickSettingsController@quickRegister')->name('quick.register');
Route::post('setup/update', 'QuickSettingsController@updateSettings')->name('quick-setup.save');

Route::get('system/updater', 'UpdateManagerController@index')->name('update.systems');
Route::post('system/updater/install', 'UpdateManagerController@install')->name('update.install');

Route::get('settings/global/general', 'ApplicationSettingsController@general')->name('settings.global.general');
Route::get('settings/global/currencies', 'ApplicationSettingsController@currency')->name('settings.global.currency');
Route::get('settings/global/rewards', 'ApplicationSettingsController@rewards')->name('settings.global.rewards');
Route::get('settings/global/referral', 'ApplicationSettingsController@referrals')->name('settings.global.referral');
Route::get('settings/global/api', 'ApplicationSettingsController@api')->name('settings.global.api');
Route::get('settings/website', 'ApplicationSettingsController@website')->name('settings.website');
Route::get('settings/website/userpanel', 'ApplicationSettingsController@userpanel')->name('settings.website.userpanel');
Route::get('settings/website/appearance', 'ApplicationSettingsController@appearance')->name('settings.website.appearance');
Route::get('settings/website/misc', 'ApplicationSettingsController@misc')->name('settings.website.misc');
Route::get('settings/email', 'ApplicationSettingsController@email')->name('settings.email');
Route::post('settings/email/mailer', 'ApplicationSettingsController@sendTestEmail')->name('settings.email.test');
Route::get('settings/component/system', 'ApplicationSettingsController@componentSystem')->name('settings.component.system');

Route::post('settings/update', 'ApplicationSettingsController@saveSettings')->name('save.app.settings');
Route::post('settings/branding/upload', 'ApplicationSettingsController@storeBranding')->name('save.website.brands');

//Payment
Route::get('settings/gateway/deposit-withdraw', 'ApplicationSettingsController@paymentOptions')->name('settings.gateway.option');
Route::get('settings/gateway/payment-method', 'ApplicationSettingsController@paymentMethods')->name('settings.gateway.payment.list');
Route::get('settings/gateway/withdraw-method', 'ApplicationSettingsController@withdrawMethods')->name('settings.gateway.withdraw.list');

Route::post('settings/gateway/quick-update', 'ApplicationSettingsController@updateMethodActivation')->name('settings.gateway.quick');

// Investment App
Route::get('settings/investment/app', 'ApplicationSettingsController@investmentApp')->name('settings.investment.app');
Route::get('settings/investment/account', 'ApplicationSettingsController@investmentAccount')->name('settings.investment.account');

// Manage Pages
Route::get('manage/pages', 'ManagePagesController@index')->name('manage.pages');
Route::get('manage/pages/create', 'ManagePagesController@create')->name('manage.pages.create');
Route::get('manage/pages/{id}/edit', 'ManagePagesController@edit')->name('manage.pages.edit');

Route::post('manage/pages', 'ManagePagesController@save')->name('manage.pages.save');
Route::post('manage/pages/validate/slug', 'ManagePagesController@validatePageSlug')->name('manage.pages.validate.slug');
Route::post('manage/pages/delete/{id?}', 'ManagePagesController@deletePage')->name('manage.pages.delete');

// Manage Email
Route::get('manage/emails', 'ManageEmailTemplatesController@index')->name('manage.email.template');
Route::get('manage/emails/{slug}', 'ManageEmailTemplatesController@edit')->name('manage.email.template.edit');

Route::post('manage/emails', 'ManageEmailTemplatesController@save')->name('manage.email.template.save');
Route::post('manage/emails/mailer', 'ManageEmailTemplatesController@sendTestEmail')->name('manage.email.template.test');

// System Language
Route::get('/system/languages', 'LanguageController@index')->name('system.langs');
Route::get('/system/languages/{action}', 'LanguageController@actions')->name('system.langs.action');

Route::post('/system/language/create', 'LanguageController@createLang')->name('system.langs.add');
Route::post('/system/language/update/{id?}', 'LanguageController@updateLang')->name('system.langs.update');
Route::post('/system/language/delete/{id?}', 'LanguageController@deleteLang')->name('system.langs.delete');
Route::post('/system/language/process/{action}', 'LanguageController@processLang')->name('system.langs.process.action');

// Profile
Route::get('profile/{type?}', 'ProfileController@show')->name('profile.view');
Route::get('profile/settings/change-email/verify', 'ProfileController@verifyChangeEmail')->name('profile.settings.change.email.verify');
Route::post('profile/activity/clear', 'ProfileController@clearActivity')->name('profile.activity.clear');
Route::post('profile/activity/{id}', 'ProfileController@deleteActivity')->name('profile.activity.delete');

Route::post('profile/update', 'ProfileController@updatePreference')->name('profile.update');
Route::post('profile/update/personal', 'ProfileController@updatePersonalInfo')->name('profile.update.personal');
Route::post('profile/update/address', 'ProfileController@updateAddressInfo')->name('profile.update.address');

Route::post('profile/settings', 'ProfileController@saveSettings')->name('profile.settings.save');
Route::post('profile/settings/change-email', 'ProfileController@changeEmail')->name('profile.settings.change.email');
Route::post('profile/settings/change-email/resend-verify', 'ProfileController@resendVerification')->name('profile.settings.change.email.resend');
Route::post('profile/settings/change-email/cancel-request', 'ProfileController@cancelRequest')->name('profile.settings.change.email.cancel');
Route::post('profile/settings/change-password', 'ProfileController@updatePassword')->name('profile.settings.change.password');
Route::post('profile/settings/2fa/{state}', 'ProfileController@google2fa')->name('profile.settings.2fa');
Route::post('profile/preference', 'ProfileController@preference')->name('profile.preference');
