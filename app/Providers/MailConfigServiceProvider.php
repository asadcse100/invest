<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     * @version 1.0.0
     * @since 1.0
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     * @version 1.0.0
     * @since 1.0
     */
    public function boot()
    {
        $emailSettings = $this->getEmailSettings();

        if (blank($emailSettings)){
            return;
        }

        if ($fromAddress = data_get($emailSettings, 'mail_from_email', false)) {
            Config::set("mail.from.address", $fromAddress);
        }

        if ($fromName = data_get($emailSettings, 'mail_from_name', false)) {
            Config::set("mail.from.name", $fromName);
        }

        if (data_get($emailSettings, 'mail_driver') == "mail") {
            Config::set("mail.default", "sendmail");
        } else if (data_get($emailSettings, 'mail_driver') == "smtp") {
            $config = array(
                'transport'     => "smtp",
                'host'       => data_get($emailSettings, 'mail_smtp_host'),
                'port'       => data_get($emailSettings, 'mail_smtp_port'),
                'encryption' => data_get($emailSettings, 'mail_smtp_secure', null),
                'username'   => data_get($emailSettings, 'mail_smtp_user'),
                'password'   => data_get($emailSettings, 'mail_smtp_password'),
                'timeout' => null,
                'auth_mode' => null,
            );
            Config::set("mail.default", "smtp");
            Config::set("mail.mailers.smtp", $config);

        }
    }

    /**
     *@version 1.0.0
     * @since 1.0
     */
    private function getEmailSettings()
    {
        if (!file_exists(storage_path('installed'))) {
            return null;
        }
        
        if (!$this->checkSettingsTable()) {
            return null;
        }

        return Setting::whereIn("key", [
            'mail_driver',
            'mail_smtp_host',
            'mail_smtp_port',
            'mail_smtp_secure',
            'mail_smtp_user',
            'mail_smtp_password',
            'mail_from_name',
            'mail_from_email',
        ])->get()->pluck('value', 'key');
    }

    public function checkSettingsTable()
    {
        try {
            DB::connection()->getPdo();
            return Schema::hasTable("settings");
        } catch (\Exception $e) {
            if (env('APP_DEBUG', false)) {
                save_error_log($e, 'mail-configure');
            }
            return false;
        }
    }
}
