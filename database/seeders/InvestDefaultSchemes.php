<?php

namespace Database\Seeders;

use App\Enums\Boolean;
use App\Enums\InterestPeriod;
use App\Enums\InterestRateType;
use App\Enums\SchemePayout;
use App\Enums\SchemeStatus;
use App\Enums\SchemeTermTypes;
use App\Models\IvScheme;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class InvestDefaultSchemes extends Seeder
{
    private $slugKeyMap = [
        'standard-plan' => 'top_iv_plan_x1',
        'premium-plan' => 'top_iv_plan_x2',
        'professional-plan' => 'top_iv_plan_x0',
    ];

    public function run()
    {
        $schemes = [
            'standard' => [
                "name" => 'Standard Plan',
                "slug" => 'standard-plan',
                "short" => 'ST',
                "desc" => 'Entry level of investment & earn money.',
                "amount" => 10,
                "maximum" => 500,
                "is_fixed" => false,
                "term" => 21,
                "term_type" => SchemeTermTypes::DAYS,
                "rate" => 1.1,
                "rate_type" => InterestRateType::PERCENT,
                "calc_period" => InterestPeriod::DAILY,
                "days_only" => Boolean::NO,
                "capital" => Boolean::NO,
                "payout" => SchemePayout::TERM_BASIS,
                "status" => SchemeStatus::ACTIVE,
                "featured" => Boolean::NO,
            ],
            'premium' => [
                "name" => 'Premium Plan',
                "slug" => 'premium-plan',
                "short" => 'PM',
                "desc" => 'Medium level of investment & earn money.',
                "amount" => 100,
                "maximum" => 1500,
                "is_fixed" => false,
                "term" => 1,
                "term_type" => SchemeTermTypes::MONTHS,
                "rate" => 1.5,
                "rate_type" => InterestRateType::PERCENT,
                "calc_period" => InterestPeriod::DAILY,
                "days_only" => Boolean::NO,
                "capital" => Boolean::NO,
                "payout" => SchemePayout::TERM_BASIS,
                "status" => SchemeStatus::ACTIVE,
                "featured" => Boolean::NO,
            ],
            'professional' => [
                "name" => 'Professional Plan',
                "slug" => 'professional-plan',
                "short" => 'PN',
                "desc" => 'Exclusive level of investment & earn money.',
                "amount" => 500,
                "maximum" => 2500,
                "is_fixed" => false,
                "term" => 50,
                "term_type" => SchemeTermTypes::DAYS,
                "rate" => 2.5,
                "rate_type" => InterestRateType::PERCENT,
                "calc_period" => InterestPeriod::DAILY,
                "days_only" => Boolean::NO,
                "capital" => Boolean::NO,
                "payout" => SchemePayout::TERM_BASIS,
                "status" => SchemeStatus::ACTIVE,
                "featured" => Boolean::NO,
            ],
            'mercury' => [
                "name" => 'Mercury',
                "slug" => 'mercury',
                "short" => 'MC',
                "desc" => 'Investment for long term & earn money.',
                "amount" => 100,
                "maximum" => 0,
                "is_fixed" => true,
                "term" => 7,
                "term_type" => SchemeTermTypes::DAYS,
                "rate" => 0.25,
                "rate_type" => InterestRateType::PERCENT,
                "calc_period" => InterestPeriod::HOURLY,
                "days_only" => Boolean::NO,
                "capital" => Boolean::NO,
                "payout" => SchemePayout::TERM_BASIS,
                "status" => SchemeStatus::ACTIVE,
                "featured" => Boolean::YES,
            ],
            'venus' => [
                "name" => 'Venus',
                "slug" => 'venus',
                "short" => 'VN',
                "desc" => 'Investment for long term & earn money.',
                "amount" => 250,
                "maximum" => 0,
                "is_fixed" => true,
                "term" => 1,
                "term_type" => SchemeTermTypes::MONTHS,
                "rate" => 5,
                "rate_type" => InterestRateType::PERCENT,
                "calc_period" => InterestPeriod::DAILY,
                "days_only" => Boolean::NO,
                "capital" => Boolean::NO,
                "payout" => SchemePayout::TERM_BASIS,
                "status" => SchemeStatus::ACTIVE,
                "featured" => Boolean::YES,
            ],
            'jupiter' => [
                "name" => 'Jupiter',
                "slug" => 'jupiter',
                "short" => 'JP',
                "desc" => 'Investment for long term & earn money.',
                "amount" => 500,
                "maximum" => 0,
                "is_fixed" => true,
                "term" => 3,
                "term_type" => SchemeTermTypes::MONTHS,
                "rate" => 20,
                "rate_type" => InterestRateType::PERCENT,
                "calc_period" => InterestPeriod::WEEKLY,
                "days_only" => Boolean::NO,
                "capital" => Boolean::NO,
                "payout" => SchemePayout::TERM_BASIS,
                "status" => SchemeStatus::ACTIVE,
                "featured" => Boolean::YES,
            ],
            'silver-plan' => [
                "name" => 'Silver Plan',
                "slug" => 'silver-plan',
                "short" => 'SV',
                "desc" => 'Investment for long term & earn money.',
                "amount" => 100,
                "maximum" => 0,
                "is_fixed" => true,
                "term" => 7,
                "term_type" => SchemeTermTypes::DAYS,
                "rate" => 0.25,
                "rate_type" => InterestRateType::PERCENT,
                "calc_period" => InterestPeriod::HOURLY,
                "days_only" => Boolean::NO,
                "capital" => Boolean::NO,
                "payout" => SchemePayout::TERM_BASIS,
                "status" => SchemeStatus::INACTIVE,
                "featured" => Boolean::NO,
            ],
            'dimond-plan' => [
                "name" => 'Dimond Plan',
                "slug" => 'dimond-plan',
                "short" => 'DM',
                "desc" => 'Investment for long term & earn money.',
                "amount" => 250,
                "maximum" => 0,
                "is_fixed" => true,
                "term" => 1,
                "term_type" => SchemeTermTypes::MONTHS,
                "rate" => 5,
                "rate_type" => InterestRateType::PERCENT,
                "calc_period" => InterestPeriod::DAILY,
                "days_only" => Boolean::NO,
                "capital" => Boolean::NO,
                "payout" => SchemePayout::TERM_BASIS,
                "status" => SchemeStatus::INACTIVE,
                "featured" => Boolean::NO,
            ],
            'platinum-plan' => [
                "name" => 'Platinum Plan',
                "slug" => 'platinum-plan',
                "short" => 'JP',
                "desc" => 'Investment for long term & earn money.',
                "amount" => 500,
                "maximum" => 0,
                "is_fixed" => true,
                "term" => 3,
                "term_type" => SchemeTermTypes::MONTHS,
                "rate" => 20,
                "rate_type" => InterestRateType::PERCENT,
                "calc_period" => InterestPeriod::WEEKLY,
                "days_only" => Boolean::NO,
                "capital" => Boolean::NO,
                "payout" => SchemePayout::TERM_BASIS,
                "status" => SchemeStatus::INACTIVE,
                "featured" => Boolean::NO,
            ],
        ];

        foreach ($schemes as $slug => $scheme) {
            $exist = IvScheme::where('slug', $scheme['slug'])->count();
            if ($exist <= 0) {
                $ivScheme = IvScheme::create($scheme);
                if (in_array($ivScheme->slug, array_keys($this->slugKeyMap))) {
                    Setting::updateOrCreate(['key' => $this->slugKeyMap[$ivScheme->slug]],['value' => $ivScheme->id]);
                }
            }
        }

    }
}
