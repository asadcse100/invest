<?php


namespace App\Updates;


use App\Enums\Boolean;
use App\Enums\InterestPeriod;
use App\Enums\InterestRateType;
use App\Enums\SchemePayout;
use App\Enums\SchemeStatus;
use App\Enums\SchemeTermTypes;
use App\Models\IvScheme;
use Illuminate\Support\Facades\File;

class MigrationVer2110110 implements UpdaterInterface
{
    const VERSION = 21101101;

    public function getVersion()
    {
        return self::VERSION;
    }

    public function handle()
    {
        $this->addNewSchemes();
        $this->addNewSettings();
        $this->fixMailLogo();
    }

    private function addNewSettings() {
        if (!empty(hss('installed_apps')) && hss('deposit_service') == null) {
            upss('deposit_service', cipher(sys_info('ptype'), true));
        }
        if (!empty(hss('installed_apps')) && hss('withdraw_service') == null) {
            upss('withdraw_service', cipher(sys_info('ptype')));
        }
        if (hss('language_default_public') == null) {
            upss('language_default_public', 'en');
        }
        if (hss('language_default_system') == null) {
            upss('language_default_system', 'en');
        }
        if (hss('language_show_as') == null) {
            upss('language_show_as', 'default');
        }
        if (hss('language_switcher') == null) {
            upss('language_switcher', 'off');
        }
    }

    private function fixMailLogo() {
        $brand = hss('website_logo_mail');

        if ($brand) {
            $ds   = DIRECTORY_SEPARATOR;
            $path = storage_path('app/'.hss('website_logo_mail'));
            $logo = str_replace(['/', '\\'], [$ds, $ds], $path);

            if (File::exists($logo)) {
                $extn  = File::extension($logo);
                File::copy($logo, public_dir('images/logo-mailer.'.$extn));
            }
        }
    }

    private function addNewSchemes() {
        $schemes = [
            'investment-plan-x1' => [
                "name" => 'Investment Plan X1',
                "slug" => 'investment-plan-x1',
                "short" => 'X1',
                "desc" => 'Invest your money and & earn.',
                "amount" => 10,
                "maximum" => 500,
                "is_fixed" => false,
                "term" => 7,
                "term_type" => SchemeTermTypes::DAYS,
                "rate" => 1.1,
                "rate_type" => InterestRateType::PERCENT,
                "calc_period" => InterestPeriod::DAILY,
                "days_only" => Boolean::NO,
                "capital" => Boolean::NO,
                "payout" => SchemePayout::TERM_BASIS,
                "status" => SchemeStatus::INACTIVE,
                "featured" => Boolean::NO,
            ],
            'investment-plan-x2' => [
                "name" => 'Investment Plan X2',
                "slug" => 'investment-plan-x2',
                "short" => 'X2',
                "desc" => 'Invest your money and & earn.',
                "amount" => 10,
                "maximum" => 500,
                "is_fixed" => false,
                "term" => 7,
                "term_type" => SchemeTermTypes::DAYS,
                "rate" => 1.1,
                "rate_type" => InterestRateType::PERCENT,
                "calc_period" => InterestPeriod::DAILY,
                "days_only" => Boolean::NO,
                "capital" => Boolean::NO,
                "payout" => SchemePayout::TERM_BASIS,
                "status" => SchemeStatus::INACTIVE,
                "featured" => Boolean::NO,
            ],
            'investment-plan-x3' => [
                "name" => 'Investment Plan X3',
                "slug" => 'investment-plan-x3',
                "short" => 'X3',
                "desc" => 'Invest your money and & earn.',
                "amount" => 10,
                "maximum" => 500,
                "is_fixed" => false,
                "term" => 7,
                "term_type" => SchemeTermTypes::DAYS,
                "rate" => 1.1,
                "rate_type" => InterestRateType::PERCENT,
                "calc_period" => InterestPeriod::DAILY,
                "days_only" => Boolean::NO,
                "capital" => Boolean::NO,
                "payout" => SchemePayout::TERM_BASIS,
                "status" => SchemeStatus::INACTIVE,
                "featured" => Boolean::NO,
            ],
            'investment-plan-x4' => [
                "name" => 'Investment Plan X4',
                "slug" => 'investment-plan-x4',
                "short" => 'X4',
                "desc" => 'Invest your money and & earn.',
                "amount" => 10,
                "maximum" => 500,
                "is_fixed" => false,
                "term" => 7,
                "term_type" => SchemeTermTypes::DAYS,
                "rate" => 1.1,
                "rate_type" => InterestRateType::PERCENT,
                "calc_period" => InterestPeriod::DAILY,
                "days_only" => Boolean::NO,
                "capital" => Boolean::NO,
                "payout" => SchemePayout::TERM_BASIS,
                "status" => SchemeStatus::INACTIVE,
                "featured" => Boolean::NO,
            ],
            'investment-plan-x5' => [
                "name" => 'Investment Plan X5',
                "slug" => 'investment-plan-x5',
                "short" => 'X5',
                "desc" => 'Invest your money and & earn.',
                "amount" => 10,
                "maximum" => 500,
                "is_fixed" => false,
                "term" => 7,
                "term_type" => SchemeTermTypes::DAYS,
                "rate" => 1.1,
                "rate_type" => InterestRateType::PERCENT,
                "calc_period" => InterestPeriod::DAILY,
                "days_only" => Boolean::NO,
                "capital" => Boolean::NO,
                "payout" => SchemePayout::TERM_BASIS,
                "status" => SchemeStatus::INACTIVE,
                "featured" => Boolean::NO,
            ],
            'investment-plan-x6' => [
                "name" => 'Investment Plan X6',
                "slug" => 'investment-plan-x6',
                "short" => 'X6',
                "desc" => 'Invest your money and & earn.',
                "amount" => 10,
                "maximum" => 500,
                "is_fixed" => false,
                "term" => 7,
                "term_type" => SchemeTermTypes::DAYS,
                "rate" => 1.1,
                "rate_type" => InterestRateType::PERCENT,
                "calc_period" => InterestPeriod::DAILY,
                "days_only" => Boolean::NO,
                "capital" => Boolean::NO,
                "payout" => SchemePayout::TERM_BASIS,
                "status" => SchemeStatus::INACTIVE,
                "featured" => Boolean::NO,
            ]
        ];

        foreach ($schemes as $slug => $scheme) {
            $exist = IvScheme::where('slug', $scheme['slug'])->count();
            if ($exist <= 0) {
                IvScheme::create($scheme);
            }
        }
    }
}
