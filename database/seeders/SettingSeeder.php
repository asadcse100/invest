<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            'site_name' => "Investorm",
            'site_email' => "info@yourdomain.com",
            'site_copyright' => ":Sitename &copy; :year. All Rights Reserved.",
            'site_disclaimer' => "",
            'site_merchandise' => null,
            'main_website' => "",
            'front_page_enable' => "yes",
            'front_page_extra' => "on",
            'front_page_title' => "Welcome",
            'invest_page_enable' => "yes",

            'time_zone' => "Asia/Dhaka",
            'date_format' => "d M, Y",
            'time_format' => "h:i A",

            'decimal_fiat_display' => 2,
            'decimal_crypto_display' => 4,
            'decimal_fiat_calc' => 2,
            'decimal_crypto_calc' => 6,

            'signup_allow' => "enable",
            'email_verification' => "on",
            'batch_update' => 120,

            'maintenance_mode' => "off",
            'maintenance_notice' => "We are upgrading our system. Please check after 30 minutes.",

            'mail_from_name' => "Investorm",
            'mail_from_email' => "noreply@yourdomain.com",
            'mail_global_footer' => "Best Regard, \nTeam of [[site_name]]",
            'mail_driver' => "mail",
            'mail_smtp_host' => "mail.yourdomain.com",
            'mail_smtp_port' => "587",
            'mail_smtp_secure' => "tls",
            'mail_smtp_user' => "",
            'mail_smtp_password' => "",
            'mail_recipient' => "",
            'mail_recipient_alter' => "",

            'youtube_link' => "",
            'linkedin_link' => "",
            'twitter_link' => "",
            'facebook_link' => "",
            'medium_link' => "",
            'telegram_link' => "",
            'github_link' => "",
            'pinterest_link' => "",
            'app_acquire' => "",

            'exratesapi_access_key' => "",
            'recaptcha_site_key' => "",
            'recaptcha_secret_key' => "",

            'custom_stylesheet' => "off",
            'header_code' => "",
            'footer_code' => "",

            'main_nav' => json_encode([]),
            'main_menu' => json_encode([]),
            'footer_menu' => json_encode([]),
            'page_terms' => "",
            'page_privacy' => "",
            'page_fee_deposit' => "",
            'page_fee_withdraw' => "",
            'page_contact' => "",
            'page_contact_form' => "on",

            'ui_page_skin' => "dark",
            'ui_auth_skin' => "dark",
            'ui_auth_layout' => "default",
            'ui_theme_mode' => "light",
            'ui_theme_skin' => "default",
            'ui_sidebar_user' => "white",
            'ui_sidebar_admin' => "darker",
            'ui_theme_mode_admin' => "light",
            'ui_theme_skin_admin' => "default",

            'payout_batch' => null,
            'signup_bonus_allow' => "no",
            'signup_bonus_amount' => 0,
            'deposit_bonus_allow' => "no",
            'deposit_bonus_amount' => 0,
            'deposit_bonus_type' => "fixed",

            'referral_system' => "no",
            'referral_invite_title' => "Refer Us & Earn",
            'referral_invite_text' => "Use the below link to invite your friends.",
            'referral_signup_user' => "no",
            'referral_signup_user_bonus' => 0,
            'referral_signup_user_reward' => "no",
            'referral_deposit_user' => "no",
            'referral_deposit_user_bonus' => 0,
            'referral_deposit_user_type' => "percent",
            'referral_signup_referer' => "no",
            'referral_signup_referer_bonus' => 0,
            'referral_deposit_referer' => "no",
            'referral_deposit_referer_bonus' => 0,
            'referral_deposit_referer_type' => "percent",

            'alert_wd_account' => "on",
            'alert_profile_basic' => "on",
            'header_notice_show' => "no",
            'header_notice_title' => "",
            'header_notice_text' => "",
            'header_notice_link' => "",
            'system_service' => null,
            'api_service' => null,

            'deposit_service' => 'l1',
            'deposit_limit_request' => 0,
            'deposit_cancel_timeout' => 'yes',
            'deposit_fiat_minimum' => 1,
            'deposit_crypto_minimum' => 0,
            'deposit_fiat_maximum' => 0,
            'deposit_crypto_maximum' => 0,
            'deposit_disable_request' => "no",
            'deposit_disable_title' => "Temporarily unavailable!",
            'deposit_disable_notice' => "Sorry, we are upgrading our deposit system. Please check after sometimes. We apologize for any inconvenience.",

            'payout_check' => null,
            'withdraw_service' => 'l1',
            'withdraw_limit_request' => 0,
            'withdraw_cancel_timeout' => 'yes',
            'withdraw_fiat_minimum' => 10,
            'withdraw_fiat_maximum' => 0,
            'withdraw_crypto_minimum' => 0,
            'withdraw_crypto_maximum' => 0,
            'withdraw_disable_request' => "no",
            'withdraw_disable_title' => "Temporarily unavailable!",
            'withdraw_disable_notice' => "Sorry, we are upgrading our withdrawal system. Please check after sometimes. We apologize for any inconvenience.",

            'app_queue' => 0,
            'base_currency' => "USD",
            'alter_currency' => "GBP",
            'supported_currency' => json_encode(["USD" => "on", "EUR" => "on", "GBP" => "on", "CAD" => "on", "BTC" => "on", "ETH" => "on", "LTC" => "on", "BNB" => "on"]),
            'fiat_rounded' => 'up',
            'crypto_rounded' => 'none',

            'exchange_method' => "automatic",
            'exchange_auto_update' => 30,
            'exchange_last_update' => time(),
            'manual_exchange_rate' => json_encode(["USD" => 0, "EUR" => 0, "GBP" => 0, "CAD" => 0, "BTC" => 0, "ETH" => 0, "LTC" => 0, "BNB" => 0]),
            'health_checker' => 0,

            'top_iv_plan_x0' => '',
            'top_iv_plan_x1' => '',
            'top_iv_plan_x2' => '',
        ];

        if (empty($settings)) {
            return;
        }

        foreach ($settings as $key => $value) {
            $exist = Setting::where('key', $key)->count();
            if ($exist <= 0) {
                Setting::create([
                    'key' => $key,
                    'value' => $value
                ]);
            }
        }
    }
}
