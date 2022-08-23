<?php

namespace App\Services;

use App\Models\IvInvest;
use App\Enums\InterestPeriod;
use App\Enums\SchemeTermTypes;
use App\Enums\InvestmentStatus;

use App\Services\Investment\IvSubscription;
use App\Services\Investment\IvPayoutProcess;
use App\Services\Investment\IvProfitCalculator;
use App\Services\Investment\IvInvestmentProcessor;

class InvestormService extends Service
{
    const MIN_APP_VER = '1.1.0';
    const SLUG = 'investment';
    const TERM_CONVERSION = [
            SchemeTermTypes::YEARS => [
                InterestPeriod::YEARLY => 1,
                InterestPeriod::MONTHLY => 12,
                InterestPeriod::WEEKLY => 52,
                InterestPeriod::DAILY => 365,
                InterestPeriod::HOURLY => 8760,
            ],
            SchemeTermTypes::MONTHS => [
                InterestPeriod::MONTHLY => 1,
                InterestPeriod::WEEKLY => 4,
                InterestPeriod::DAILY => 30,
                InterestPeriod::HOURLY => 720,
            ],
            SchemeTermTypes::WEEKS => [
                InterestPeriod::WEEKLY => 1,
                InterestPeriod::DAILY => 7,
                InterestPeriod::HOURLY => 168,
            ],
            SchemeTermTypes::DAYS => [
                InterestPeriod::DAILY => 1,
                InterestPeriod::HOURLY => 24,
            ],
            SchemeTermTypes::HOURS => [
                InterestPeriod::HOURLY => 1,
            ]
        ];

    const INTERVALS = [
        InterestPeriod::HOURLY => 1,
        InterestPeriod::DAILY => 24,
        InterestPeriod::WEEKLY => 168,
        InterestPeriod::MONTHLY => 720,
        InterestPeriod::YEARLY => 8760,
    ];

    private $ivProcessor;

    public function __construct()
    {
        $this->ivProcessor = new IvInvestmentProcessor();
    }

    public function processSubscriptionDetails($input, $ivScheme, $investAmount): array
    {
        $investmentProcessor = new IvSubscription();
        return $investmentProcessor->setScheme($ivScheme)
            ->setUser(auth()->user())
            ->setInvestAmount($investAmount)
            ->setSource($input['source'])
            ->setCurrency($input['currency'])
            ->generateNewInvestmentDetails();
    }

    public function confirmSubscription($details): IvInvest
    {
        return $this->ivProcessor->setDetails($details)->processInvestment();
    }

    public function approveSubscription(IvInvest $invest, $remarks = null, $note = null)
    {
        return $this->ivProcessor->approveInvestment($invest, $remarks, $note);
    }

    public function processInvestmentProfit(IvInvest $invest)
    {
        if ($invest->status == InvestmentStatus::ACTIVE) {
            $transactionProcessor = new IvProfitCalculator();
            $transactionProcessor->setInvest($invest)
                ->calculateProfit();
        }
    }

    public function cancelSubscription(IvInvest $invest)
    {
        return $this->ivProcessor->cancelInvestment($invest);
    }

    public function proceedPayout($user_id, $profits, $entry = null)
    {
        $payoutProcess = new IvPayoutProcess();
        $payoutProcess->setUser($user_id)
                ->setPayout($profits)
                ->payProfits($entry);
    }

    public function processCompleteInvestment(IvInvest $invest)
    {
        $payoutProcess = new IvPayoutProcess();
        $payoutProcess->setUser($invest->user_id)->completeInvest($invest);
    }
}
