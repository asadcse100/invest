<?php

namespace App\Updates;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrationVer2120115 implements UpdaterInterface
{
    const VERSION = 21201151;

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
        if (hss('header_notice_date') == null) {
            upss('header_notice_date', '');
        }

        if (hss('deposit_amount_base') == null) {
            upss('deposit_amount_base', 'yes');
        }

        if (hss('rates_ticker_display') == null) {
            upss('rates_ticker_display', 'no');
        }

        if (hss('rates_ticker_from') == null) {
            upss('rates_ticker_from', 'base');
        }

        if (hss('rates_ticker_fx') == null) {
            upss('rates_ticker_fx', 'only');
        }

        if (hss('iv_plan_capital_show') == null) {
            upss('iv_plan_capital_show', 'yes');
        }

        if (hss('iv_plan_payout_show') == null) {
            upss('iv_plan_payout_show', 'no');
        }

        if (hss('iv_plan_terms_show') == null) {
            upss('iv_plan_terms_show', 'no');
        }
    }
}
