<?php


namespace App\Services\Investment;

use App\Enums\Boolean;
use App\Enums\ActionType;
use App\Enums\ProfitPayout;
use App\Enums\LedgerTnxType;
use App\Enums\InvestmentStatus;
use App\Enums\TransactionCalcType;

use App\Models\IvAction;
use App\Models\IvInvest;
use App\Models\IvLedger;
use App\Models\IvProfit;

use Carbon\Carbon;
use Brick\Math\BigDecimal;

class IvPayoutProcess
{
    private $user_id;
    private $profits;

    public function setUser(int $user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function setPayout(array $profits)
    {
        $this->profits = $profits;
        return $this;
    }

    public function payoutMode()
    {
        return sys_settings('iv_profit_payout', ProfitPayout::EVERYTIME);
    }

    public function payoutThreshold()
    {
        return (float)sys_settings('iv_profit_payout_amount', 0);
    }

    public function hasThreshold()
    {
        return ($this->payoutMode() == ProfitPayout::THRESHOLD && BigDecimal::of($this->payoutThreshold())->compareTo(0) == 1) ? true : false;
    }

    public function payProfits($entry = null)
    {
        $batch = time();

        if ($entry === 'manual') {
            foreach ($this->profits as $profit_id) {
                $this->payoutProfit($profit_id, $batch, $entry);
            }
        } elseif ($this->hasThreshold()) {
            $threshold = $this->payoutThreshold();
            $profits = IvProfit::findMany($this->profits);

            if (!blank($profits)) {
                $amount = $profits->sum('amount');
                if (BigDecimal::of($amount)->compareTo($threshold) == -1) {
                    return;
                }

                $ledger = $this->makeLedgerEntry($amount, $batch, $this->profits);

                if (!blank($ledger)) {
                    IvProfit::whereIn('id', $this->profits)->update(['payout' => $ledger->reference]);
                }
            }
        } else {
            foreach ($this->profits as $profit_id) {
                $this->payoutProfit($profit_id, $batch);
            }
        }
    }

    private function payoutProfit ($id, $batch, $entry = null) {
        $profit = IvProfit::find($id);
        if (!blank($profit)) {
            $ledger = $this->makeLedgerEntry($profit->amount, $batch, $profit, $entry);

            if (!blank($ledger)) {
                $profit->payout = $ledger->reference;
                $profit->save();
            }
        }
    }

    private function makeLedgerEntry($amount, $batch = null, $profit = null, $entry = null)
    {
        $batch = (empty($batch)) ? time() : $batch;
        $meta  = $this->toMetaData($profit, $entry);

        $data = [
            'ivx' => generate_unique_ivx(IvLedger::class, 'ivx'),
            'user_id' => $this->user_id,
            'type' => LedgerTnxType::PROFIT,
            'calc' => TransactionCalcType::CREDIT,
            'amount' => $amount,
            'fees' => 0,
            'total' => to_sum($amount, 0),
            'currency' => base_currency(),
            'desc' => "Profit Earned",
            'invest_id' => (isset($profit->invest_id)) ? $profit->invest_id : 0,
            'reference' => $batch,
            'meta' => ($meta && is_array($meta)) ? json_encode($meta) : null, 
            'source' => AccType('invest'),
            'created_at' => Carbon::now(),
        ];

        $ledger = new IvLedger();
        $ledger->fill($data);
        $ledger->save();

        $this->updateBalance($ledger->amount);

        return $ledger;
    }

    private function toMetaData($profit = null, $entry = null)
    {
        $data = [];
        if ($this->hasThreshold()) {
            $data['payout'] = ProfitPayout::THRESHOLD;
            $data['limit'] = $this->payoutThreshold();
        }

        if (in_array($entry, ['remain', 'manual'])) {
            $data['entry'] = $entry;
        }

        return $data;
    }

    private function updateBalance($amount, $type = null)
    {
        if (BigDecimal::of($amount)->compareTo(0) != 1) {
            return;
        }

        $type = (empty($type)) ? 'add' : $type;
        $account = get_user_account($this->user_id, AccType('invest'));
        $account->amount = ($type == 'add') ? to_sum($account->amount, $amount) : to_minus($account->amount, $amount);
        $account->save();
    }

    public function completeInvest(IvInvest $invest)
    {
        $termCount = IvProfit::where('user_id', $invest->user_id)
            ->where('invest_id', $invest->id)
            ->count();

        if ($termCount == $invest->term_total) {
            // Adjust Remaining Profits v1.1.3
            $remainingProfits = IvProfit::where('user_id', $invest->user_id)
                ->where('invest_id', $invest->id)
                ->whereNull('payout')
                ->get();

            if (!blank($remainingProfits)) {
                $batch = time();
                $amount = $remainingProfits->sum('amount');
                $ledger = $this->makeLedgerEntry($amount, $batch, $remainingProfits->first(), 'remain');

                if (!blank($ledger)) {
                    IvProfit::whereIn('id', $remainingProfits->pluck('id')->toArray())
                        ->update(['payout' => $ledger->reference]);
                }
            }

            // Adjusted Capital End of Term
            if (data_get($invest, 'scheme.capital', 0) == Boolean::YES) {
                $data = [
                     'ivx' => generate_unique_ivx(IvLedger::class, 'ivx'),
                     'user_id' => $invest->user_id,
                     'type' => LedgerTnxType::CAPITAL,
                     'calc' => TransactionCalcType::CREDIT,
                     'amount' => $invest->amount,
                     'fees' => 0,
                     'total' => to_sum($invest->amount, 0),
                     'currency' => base_currency(),
                     'desc' => "Capital Returned",
                     'invest_id' => $invest->id,
                     'reference' => time(),
                     'source' => AccType('invest'),
                     'created_at' => Carbon::now(),
                 ];

                $ledger = new IvLedger();
                $ledger->fill($data);
                $ledger->save();

                $this->updateBalance($ledger->amount);

                $invest->received = to_sum($invest->received, $ledger->amount);
                $invest->save();
            }

            $invest->status = InvestmentStatus::COMPLETED;
            $invest->save();

            $data = [
                'action' => ActionType::STATUS_COMPLETE,
                'action_at' => Carbon::now(),
                'action_by' => (isset(system_admin()->id)) ? system_admin()->id : 0,
                'type' => 'invest',
                'type_id' => $invest->id,
            ];

            $ivAction = new IvAction();
            $ivAction->fill($data);
            $ivAction->save();
        }
    }
}
