<?php


namespace App\Services\Investment;

use App\Enums\Boolean;
use App\Enums\InterestRateType;
use App\Enums\InvestmentStatus;
use App\Enums\ActionType;

use App\Models\IvInvest;
use App\Models\IvProfit;
use App\Models\IvAction;
use App\Services\InvestormService;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;

use Illuminate\Support\Facades\Log;

class IvProfitCalculator
{
    private $invest;

    public function setInvest(IvInvest $invest): self
    {
        $this->invest = $invest;

        return $this;
    }

    private function calculateCapital()
    {
        $capital = BigDecimal::of(0);
        $scale   = (is_crypto($this->invest->currency)) ? dp_calc('crypto') : dp_calc('fiat');

        if (data_get($this->invest, 'scheme.capital', 0) == Boolean::NO) {
            $capital = BigDecimal::of($this->invest->amount)->dividedBy($this->invest->term_total, $scale, RoundingMode::CEILING);
        }

        return $capital;
    }

    private function calculateInterest()
    {
        $profit = BigDecimal::of(0);
        $rate   = data_get($this->invest, 'scheme.rate');
        $type   = data_get($this->invest, 'scheme.rate_type');
        $scale  = (is_crypto($this->invest->currency)) ? dp_calc('crypto') : dp_calc('fiat');

        if ($type == InterestRateType::FIXED) {
            $profit = BigDecimal::of($rate);
        } elseif ($type == InterestRateType::PERCENT) {
            $profit = BigDecimal::of($this->invest->amount)->multipliedBy(BigDecimal::of($rate))->dividedBy(100, $scale, RoundingMode::CEILING);
        }

        return $profit;
    }

    private function calculateTermAmount()
    {
        $profit = $this->calculateInterest();
        $capital = $this->calculateCapital();

        return $profit->plus($capital);
    }

    private function makeProfitEntry($period, $lastProfitEntry)
    {
        $termNo = blank($lastProfitEntry) ? 1 : ($lastProfitEntry->term_no+1);
        $termAmount = $this->calculateTermAmount();

        $data = [
            "user_id" => $this->invest->user_id,
            "invest_id" => $this->invest->id,
            "amount" => $termAmount,
            "capital" => $this->calculateCapital(),
            "invested" => $this->invest->amount,
            "currency" => $this->invest->currency,
            "rate" => data_get($this->invest, 'scheme.rate'),
            "type" => strtoupper(substr(data_get($this->invest, 'scheme.rate_type'), 0, 1)),
            "calc_at" => $period,
            "term_no" => $termNo,
        ];

        $profit = new IvProfit();
        $profit->fill($data);
        $profit->save();

        $invest = IvInvest::find($this->invest->id);
        $invest->received = to_sum($invest->received, $termAmount);
        $invest->term_count = $termNo;
        $invest->save();

        return $profit;
    }

    private function existingProfitCount(): int
    {
        return IvProfit::where('user_id', $this->invest->user_id)
            ->where('invest_id', $this->invest->id)
            ->count();
    }

    private function lastProfitEntry()
    {
        return IvProfit::where('user_id', $this->invest->user_id)
                ->where('invest_id', $this->invest->id)
                ->orderBy('id', 'desc')->first();
    }

    public function calculateProfit()
    {
        $profitCount = $this->existingProfitCount();

        if ($profitCount == $this->invest->term_total) {
            return;
        }

        if ($profitCount < $this->invest->term_total) {
            $lastProfitEntry = $this->lastProfitEntry();
            $interval = InvestormService::INTERVALS[data_get($this->invest, 'term_calc', data_get($this->invest, 'scheme.calc_period'))] ?? null;

            if (empty($interval)) {
                Log::error("profit-interval-invalid", $this->invest);
                return;
            }

            $period = blank($lastProfitEntry) ? $this->invest->term_start->addHours($interval) : $lastProfitEntry->calc_at->addHours($interval);
            if ($period->gt(CarbonImmutable::now())) {
                return;
            }

            $this->makeProfitEntry($period, $lastProfitEntry);

            return $this->calculateProfit();
        }
    }
}
