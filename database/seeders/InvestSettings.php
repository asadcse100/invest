<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class InvestSettings extends Seeder
{
    public static function run()
    {
        $settings = [
            'plan_order' => 'featured',
            'show_plans' => 'default',
            'plan_desc_show' => 'no',
            'plan_total_percent' => 'yes',
            'plan_pg_heading' => 'Investment Plans',
            'plan_pg_title' => 'Choose your favourite plan and start earning now.',
            'plan_pg_text' => 'Here is our several investment plans. You can invest daily, weekly or monthly and get higher returns in your investment.',
            'launched_date' => date('m/d/Y'),
            'cancel_timeout' => 15,
            'admin_confirmtion' => "yes",
            'disable_purchase' => "no",
            'disable_title' => "Temporarily unavailable!",
            'disable_notice' => "",
            'profit_payout' => "everytime",
            'profit_payout_amount' => 100,
            'plan_fx_currencies' => json_encode([]),
            'weekend_days' => json_encode([]),
        ];

        if (empty($settings)) {
            return;
        }

        foreach ($settings as $key => $value) {
            $iv_key = 'iv_'.$key;
            $exist = Setting::where('key', $iv_key)->count();
            if ($exist <= 0) {
                Setting::create([
                    'key' => $iv_key,
                    'value' => $value
                ]);
            }
        }
    }
}
