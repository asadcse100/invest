<?php

namespace App\Updates;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrationVer2118114 implements UpdaterInterface
{
    const VERSION = 21181141;

    public function getVersion()
    {
        return self::VERSION;
    }

    public function handle()
    {
        $this->addNewSettings();
    }

    private function addNewSettings()
    {
        if (hss('seo_description') == null) {
            upss('seo_description', '');
        }

        if (hss('seo_description') == null) {
            upss('seo_description', '');
        }

        if (hss('login_seo_title') == null) {
            upss('login_seo_title', '');
        }

        if (hss('registration_seo_title') == null) {
            upss('registration_seo_title', '');
        }

        if (hss('registration_seo_title') == null) {
            upss('registration_seo_title', '');
        }

        if (hss('og_title') == null) {
            upss('og_title', '');
        }

        if (hss('og_description') == null) {
            upss('og_description', '');
        }
    }
}
