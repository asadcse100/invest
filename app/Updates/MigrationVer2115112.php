<?php

namespace App\Updates;

use App\Models\IvInvest;
use App\Models\IvScheme;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MigrationVer2115112 implements UpdaterInterface
{
    const VERSION = 21151121;

    public function getVersion()
    {
        return self::VERSION;
    }

    public function handle()
    {
        $this->makeSchemeRelations();
        $this->addNewSettings();
    }

    private function makeSchemeRelations()
    {
        if (!Schema::hasColumn('iv_invests', 'scheme_id')) return;
        IvInvest::whereNull('scheme_id')->get()->each(function ($investment) {
            $name = data_get($investment, 'scheme.name');
            $slug = !empty($name) ? Str::slug($name) : null;
            $scheme = !empty($slug) ? IvScheme::where('slug', $slug)->first() : null;
            $investment->scheme_id = blank($scheme) ? 0 : $scheme->id;
            $investment->save();
        });
    }

    private function addNewSettings()
    {
        if (hss('social_auth') == null) {
            upss('social_auth', 'off');
        }

        if (hss('gdpr_enable') == null) {
            upss('gdpr_enable', 'no');
        }

        if (hss('cookie_consent_text') == null) {
            upss('cookie_consent_text', 'This website uses cookies. By continuing to use this website, you agree to their use. For details, please check our [[privacy]].');
        }

        if (hss('referral_show_referred_users') == null) {
            upss('referral_show_referred_users', 'no');
        }

        if (hss('referral_user_table_opts') == null) {
            upss('referral_user_table_opts', ['earning', 'compact']);
        }

        if (hss('referral_invite_redirect') == null) {
            upss('referral_invite_redirect', 'register');
        }

        if (hss('cookie_banner_position') == null) {
            upss('cookie_banner_position', 'bbox-left');
        }

        if (hss('cookie_banner_background') == null) {
            upss('cookie_banner_background', 'light');
        }
    }
}
