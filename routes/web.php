<?php

use Illuminate\Support\Facades\Route;
// use ProfileController;

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

Route::get('/', 'PublicController@welcome')->name('welcome');
Route::get('/language', 'PublicController@language')->name('language');
Route::get('/investments', 'PublicController@investments')->name('investments');
Route::get('/page/{slug}', 'PageController')->name('show.page');
Route::post('/policy-consent', 'PublicController@gdprCookie')->name('gdpr.cookie');

Route::middleware(['guest'])->group(function(){

	Route::get('/login', 'AuthController@loginForm')->name('auth.login.form');
	Route::post('/login', 'AuthController@login')->name('auth.login');
	Route::post('/auth/social/confirm', 'AuthController@confirmSocialAuth')->name('auth.social.confirm');
	Route::get('/auth/social/confirm', 'AuthController@confirmSocialView')->name('auth.social.confirm.signup');
	Route::get('/auth/social/verify','AuthController@authSocialVerify')->name('auth.social.verify');
	Route::get('/auth/social/{type}','AuthController@socialAuth')->name('auth.social');
	Route::get('/auth/2fa','AuthController@authVerifyForm')->name('auth.2fa.form');
	Route::post('/auth/2fa','AuthController@authVerify2FA')->name('auth.2fa');

	Route::get('/register', 'AuthController@registerForm')->name('auth.register.form');
	Route::post('/register', 'AuthController@register')->name('auth.register');

	Route::get('/password/forget', 'AuthController@forgetPasswordView')->name('auth.forget.form');
	Route::post('/password/forget', 'AuthController@forgetPassword')->name('auth.forget');

});
Route::get('/auth/social/{type}/callback', 'AuthController@socialCallback')->name('auth.social.callback');
Route::get('/password/reset', 'AuthController@resetPasswordView')->name('auth.reset.page');
Route::post('/password/reset', 'AuthController@resetPassword')->name('auth.reset');


Route::get('/email/confirm', 'AuthController@confirm')->name('auth.confirm');
Route::get('/verify', 'AuthController@verifyEmail')->name('auth.email.verify');
Route::get('/verify/email', 'AuthController@verifyEmailUpdate')->name('auth.email.update.verify');

Route::get('/account/verify','AuthController@accountVerification')->name('auth.email.verification');
Route::post('/email/resend','AuthController@resendVerifyEmail')->name('auth.email.resend');
Route::post('/email/change','AuthController@updateEmailAndVerify')->name('auth.email.change');
Route::post('/submit-form', 'ContactController@contact')->name('contact.form');

Route::post('logout', 'AuthController@logout')->middleware('auth')->name('auth.logout');
Route::get('invite', 'AuthController@referral')->name('auth.invite');

// Route while maintaince enable
Route::get('maintenance', 'MaintenanceController@index')->name('maintenance');
Route::get('/admin/login', 'AuthController@loginForm')->name('admin.login');


Route::get('dashboard', 'UserDashboardController@index')->name('dashboard');
Route::get('profile', 'ProfileController@view')->name('account.profile');
Route::get('profile/accounts', 'WithdrawAccountController@view')->name('account.withdraw-accounts');
Route::get('profile/settings', 'SettingsController@view')->name('account.settings');

Route::get('profile/activity', 'ActivityController@view')->name('account.activity');
Route::get('profile/activity/clear', 'ActivityController@clearActivity')->name('account.activity.clear');
Route::get('profile/activity/{id}', 'ActivityController@destroy')->name('account.activity.delete');

Route::post('profile/personal', 'ProfileController@savePersonalInfo')->name('account.profile.personal');
Route::post('profile/address', 'ProfileController@saveAddressInfo')->name('account.profile.address');
Route::post('profile/change-unverified-email', 'ProfileController@updateUnverifiedEmail')->name('account.profile.update-unverified-email');
Route::post('profile/verify-unverified-email/{user}', 'ProfileController@verifyUnverifiedEmail')->name('account.profile.verify-unverified-email');

Route::post('profile/preference', 'SettingsController@preference')->name('account.preference');
Route::post('profile/settings', 'SettingsController@saveSettings')->name('account.settings.save');
Route::post('profile/settings/email', 'SettingsController@changeEmail')->name('account.settings.change.email');
Route::post('profile/settings/email/resend', 'SettingsController@resendVerification')->name('account.settings.change.email.resend');
Route::post('profile/settings/email/cancel', 'SettingsController@cancelRequest')->name('account.settings.change.email.cancel');
Route::post('profile/settings/password', 'SettingsController@changePassword')->name('account.settings.change.password');
Route::post('profile/settings/password/new', 'SettingsController@addPassword')->name('account.settings.add.password');
Route::post('profile/settings/2fa/{state}', 'SettingsController@google2fa')->name('account.settings.2fa');
Route::post('profile/settings/social/{platform}/{action}', 'SettingsController@social')->name('account.settings.social');
Route::post('update/setting', 'SettingsController@updateUserSettings')->name('update.setting');

Route::get('deposit', 'TransactionController@depositPaymentMethod')->name('deposit');
Route::get('deposit/online/{status}/{tnx?}', 'TransactionController@onlineDepositComplete')->name('deposit.complete.online');
Route::get('deposit/{status}/{tnx?}', 'TransactionController@depositComplete')->name('deposit.complete');
Route::post('deposit/amount', 'TransactionController@depositAmount')->name('deposit.amount.form');
Route::post('deposit/preview', 'TransactionController@depositPreview')->name('deposit.preview.form');
Route::post('deposit/confirm', 'TransactionController@depositConfirm')->name('deposit.confirm');

Route::get('withdraw', 'TransactionController@showWithdrawMethod')->name('withdraw');
Route::get('withdraw/redirect/amount', 'TransactionController@withdrawAmount')->name('withdraw.redirect.amount');
Route::post('withdraw/amount', 'TransactionController@withdrawAmount')->name('withdraw.amount.form');
Route::post('withdraw/preview', 'TransactionController@withdrawPreview')->name('withdraw.preview.form');
Route::post('withdraw/confirm', 'TransactionController@withdrawConfirm')->name('withdraw.confirm');

Route::get('transactions', 'TransactionController@list')->name('transaction.list');
Route::get('transactions/details', 'TransactionController@viewTransactionDetails')->name('transaction.details');
Route::get('transaction/{status}/{tnx?}', 'TransactionController@actionTransactionStatus')->name('transaction.action');

Route::get('referrals', 'ReferralController@index')->name('referrals');

// Welcome setup
// Route::get('welcome', ProfileController::class, 'welcome')->name('account.welcome');
Route::get('congratulation', 'ProfileController@congrats')->name('account.congrats');

Route::post('profile/complete', 'ProfileController@completeProfile')->name('account.profile.complete');
Route::post('validate/username', 'ProfileController@validateUsername')->name('validate.username');
