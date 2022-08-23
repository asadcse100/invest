<?php

namespace App\Services\Investment;

use App\Models\User;
use App\Models\IvInvest;
use App\Models\IvScheme;

use App\Enums\InterestRateType;
use App\Enums\InvestmentStatus;
use App\Services\InvestormService;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class IvSubscription
{
    private $scheme;
    private $investment;
    private $investAmount;
    private $source;
    private $currency;
    private $user;

    public function getScheme(): IvScheme
    {
        return $this->scheme;
    }

    public function setScheme(IvScheme $scheme): self
    {
        $this->scheme = $scheme;

        return $this;
    }

    public function getInvestment(): IvInvest
    {
        return $this->investment;
    }

    public function setInvestment(IvInvest $investment): self
    {
        $this->investment = $investment;

        return $this;
    }

    public function getInvestAmount(): float
    {
        return $this->investAmount;
    }

    public function setInvestAmount(float $investAmount): self
    {
        $this->investAmount = $investAmount;

        return $this;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function setSource($source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser($user): self
    {
        $this->user = $user;

        return $this;
    }

    private function exceptData($data)
    {
        $except = ['id', 'desc', 'slug', 'featured', 'created_at', 'updated_at', 'metas', 'plans'];

        if (is_array($data)) {
            return Arr::except($data, $except);
        }

        return $data;
    }

    private function getInvest(): float
    {
        return $this->investAmount;
    }

    private function calculateFees(): float
    {
        return 0.00;
    }

    private function calculateTotal(): float
    {
        return to_sum($this->getInvest(), $this->netProfit());
    }

    private function totalTermCount(): int
    {
        $periodPerTerm = InvestormService::TERM_CONVERSION[data_get($this->scheme, 'term_type')][data_get($this->scheme, 'calc_period')] ?? 0;
        $term = data_get($this->scheme, 'term', 0);
        $totalTermCount = ($periodPerTerm * $term);

        if (empty($totalTermCount)) {
            $schemeName = $this->scheme->name;
            Log::error("Invalid term configuration for scheme: $schemeName", ['scheme' => $this->scheme]);
        }

        return $totalTermCount;
    }

    private function netProfit(): float
    {
        $rate = data_get($this->scheme, 'rate');
        $rateType = data_get($this->scheme, 'rate_type');
        $amount = $this->getInvest();
        $count = $this->totalTermCount();
        $scale = (is_crypto($this->currency)) ? dp_calc('crypto') : dp_calc('fiat');

        $profitAmount = 0;
        if ($rateType == InterestRateType::PERCENT) {
            $profitAmount = BigDecimal::of($amount)->multipliedBy(BigDecimal::of($rate))->multipliedBy(BigDecimal::of($count))->dividedBy(100, $scale, RoundingMode::CEILING);
        } elseif ($rateType == InterestRateType::FIXED) {
            $profitAmount = BigDecimal::of($rate)->multipliedBy(BigDecimal::of($count));
        }

        $finalAmount = is_object($profitAmount) ? (string) $profitAmount : $profitAmount;
        return (float) $finalAmount;
    }

    public function generateNewInvestmentDetails(): array
    {
        $termStart = CarbonImmutable::now();
        $termEnd = $termStart->add($this->scheme->term_text)->addMinutes(1)->addSeconds(5);
        $rateShort = substr($this->scheme->rate_type, 0, 1);
        $currency = base_currency();

        if (empty($this->totalTermCount()) || empty($this->netProfit())) {
            return [];
        }

        return [
            "user_id" => data_get($this->user, 'id', auth()->user()->id),
            "amount" => $this->getInvest(),
            "profit" => $this->netProfit(),
            "total" => $this->calculateTotal(),
            "received" => 0.0,
            "currency" => $currency,
            "rate" => $this->scheme->rate . ' (' . ucfirst($rateShort) . ')',
            "term" => $this->scheme->term_text,
            "term_count" => 0,
            "term_total" => $this->totalTermCount(),
            "term_calc" => $this->scheme->calc_period,
            "scheme" => $this->exceptData($this->scheme->toArray()),
            "scheme_id" => data_get($this->scheme, 'id'),
            "status" => InvestmentStatus::PENDING,
            "source" => $this->source,
            "desc" => data_get($this->scheme, 'name') . ' - ' . data_get($this->scheme, 'calc_details'),
            "term_start" => $termStart,
            "term_end" => $termEnd,
            "meta" => array('source' => $this->source, 'fees' => 0, 'exchange' => 0),
        ];
    }
}
