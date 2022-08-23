<?php

namespace App\Services\Investment;

use App\Enums\RefundType;
use App\Enums\ActionType;
use App\Enums\LedgerTnxType;
use App\Enums\InvestmentStatus;
use App\Enums\TransactionStatus;
use App\Enums\TransactionCalcType;
use App\Enums\TransactionType;

use App\Models\IvAction;
use App\Models\IvInvest;
use App\Models\IvLedger;
use App\Models\IvProfit;
use App\Models\Transaction;
use App\Jobs\ProcessEmail;
use App\Services\Transaction\TransactionService;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Brick\Math\BigDecimal;

use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class IvInvestmentProcessor
{
    private $details;
    private $transactionService;

    public function __construct()
    {
        $this->transactionService = new TransactionService();
    }

    public function setDetails(array $details)
    {
        $this->details = $details;
        return $this;
    }

    private function toTnxMeta($invest)
    {
        $data = [
            'desc' => data_get($invest, 'desc'),
            'amount' => data_get($invest, 'amount'),
            'profit' => data_get($invest, 'profit'),
            'total' => data_get($invest, 'total'),
            'rate' => data_get($invest, 'rate'),
            'term' => data_get($invest, 'term'),
            'term_calc' => data_get($invest, 'term_calc'),
            'term_total' => data_get($invest, 'term_total'),
        ];

        return $data;
    }

    private function saveInvestment()
    {
        $invest = new IvInvest();
        $invest->fill($this->details);
        $invest->ivx = generate_unique_ivx(IvInvest::class, 'ivx');
        $invest->save();

        return $invest;
    }

    public function saveInvestTransaction($invest)
    {
        $userId = $invest->user_id;
        $source = Arr::get($invest, 'meta.source', AccType('main'));
        $account = get_user_account($userId, $source);

        $transaction = new Transaction();
        $transaction->tnx = generate_unique_tnx();
        $transaction->type = TransactionType::INVESTMENT;
        $transaction->user_id = $userId;
        $transaction->account_from = $account->id;
        $transaction->calc = TransactionCalcType::DEBIT;
        $transaction->amount = Arr::get($invest, 'amount');
        $transaction->fees = Arr::get($invest, 'meta.fees', 0);
        $transaction->total = to_sum($transaction->amount, $transaction->fees);
        $transaction->currency = Arr::get($invest, 'currency');
        $transaction->tnx_amount = Arr::get($invest, 'amount');
        $transaction->tnx_fees = Arr::get($invest, 'meta.fees', 0);
        $transaction->tnx_total = to_sum($transaction->tnx_amount, $transaction->tnx_fees);
        $transaction->tnx_currency = Arr::get($invest, 'currency');
        $transaction->tnx_method = $source;
        $transaction->exchange = Arr::get($invest, 'meta.exchange', 1);
        $transaction->status = TransactionStatus::NONE;
        $transaction->description = 'Invest on ' . Arr::get($invest, 'scheme.name');
        $transaction->meta = $this->toTnxMeta($invest);
        $transaction->pay_from = $source;
        $transaction->pay_to = AccType('invest');
        $transaction->created_by = $userId;
        $transaction->save();

        return $transaction;
    }

    private function saveIvAction($action, $type, $typeId, $actionBy = null)
    {
        $ivAction = new IvAction();
        $ivAction->action = $action;
        $ivAction->action_at = Carbon::now();
        $ivAction->type = $type;
        $ivAction->type_id = $typeId;
        $ivAction->action_by = $actionBy ?? auth()->user()->id;
        $ivAction->save();
        
        return $ivAction;
    }

    public function processInvestment(): IvInvest
    {
        $source = Arr::get($this->details, 'source', AccType('main'));
        $amount = Arr::get($this->details, 'amount');

        $userId = auth()->user()->id;
        $userAccount = get_user_account($userId, $source);
        $userBalance = $userAccount->amount;

        if (BigDecimal::of($amount)->compareTo($userBalance) > 0) {
            throw ValidationException::withMessages([
                'amount' => __('Sorry, you do not have sufficient balance in your account for investment. Please make a deposit and try again once you have sufficient balance.')
            ]);
        }

        $invest = $this->saveInvestment();

        if (!blank($invest)) {
            $fees = BigDecimal::of(data_get($invest, 'meta.fees', 0));
            $userAccount->amount = BigDecimal::of($userAccount->amount)->minus(BigDecimal::of($invest->amount))->minus($fees);
            $userAccount->save();

            $this->saveIvAction(ActionType::ORDER, "invest", $invest->id);

            return $invest;
        } else {
            throw ValidationException::withMessages(['invest' => __("Unable to invest on selected plan. Please try again or contact us if the problem persists.")]);
        }
    }

    public function approveInvestment(IvInvest $invest, $remarks = null, $note = null)
    {
        $transaction = $this->saveInvestTransaction($invest);
        if (blank($transaction)) {
            throw ValidationException::withMessages(['transaction' => __("Failed to approved the investment. Please try again or contact us if the problem persists.")]);
        }

        $transaction->status = TransactionStatus::PENDING;
        $transaction->reference = $invest->ivx;
        $transaction->save();

        $this->transactionService->confirmTransaction($transaction, [
            'id' => auth()->user()->id,
            'name' => auth()->user()->name
        ]);

        $ledger = new IvLedger();
        $ledger->ivx = generate_unique_ivx(IvLedger::class, 'ivx');
        $ledger->user_id = $invest->user_id;
        $ledger->type = LedgerTnxType::INVEST;
        $ledger->calc = TransactionCalcType::NONE;
        $ledger->amount = $invest->amount;
        $ledger->fees = 0;
        $ledger->total = to_sum($ledger->amount, $ledger->fees);
        $ledger->currency = $invest->currency;
        $ledger->desc = $transaction->description;
        $ledger->invest_id = $invest->id;
        $ledger->tnx_id = $transaction->id;
        $ledger->reference = $invest->ivx;
        $ledger->source = $transaction->pay_from;
        $ledger->dest = $transaction->pay_to;
        $ledger->save();

        $termStart = CarbonImmutable::now();
        $termTenure = sprintf("%s %s", data_get($invest, 'scheme.term'), ucfirst(data_get($invest, 'scheme.term_type')));
        $termEnd = $termStart->add($termTenure)->addMinutes(1)->addSeconds(5);

        $invest->remarks = $remarks;
        $invest->note = $note;
        $invest->reference = $transaction->tnx;
        $invest->status = InvestmentStatus::ACTIVE;
        $invest->term_start = $termStart;
        $invest->term_end = $termEnd;
        $invest->save();
        
        if (!data_get($invest, 'ivScheme.is_locked')) {
            $invest->ivScheme()->update(['is_locked' => true]);
        }

        if (iv_start_automatic()) {
            $actionBy = isset(system_admin()->id) ? system_admin()->id : 1;
            $this->saveIvAction(ActionType::STATUS_ACTIVE, "invest", $invest->id, $actionBy);
        } else {
            $this->saveIvAction(ActionType::STATUS_ACTIVE, "invest", $invest->id);
        }

        return $invest;
    }

    private function cancelPendingInvest(IvInvest $invest)
    {
        $source = data_get($invest, 'meta.source', AccType('main'));
        $fees = BigDecimal::of(data_get($invest, 'meta.fees', 0));

        $userAccount = get_user_account($invest->user_id, $source);
        $userAccount->amount = BigDecimal::of($userAccount->amount)->plus(BigDecimal::of($invest->amount))->plus($fees);
        $userAccount->save();

        $invest->status = InvestmentStatus::CANCELLED;
        $invest->save();

        $this->saveIvAction(ActionType::STATUS_CANCEL, "invest", $invest->id);

        return $invest->fresh();
    }

    public function cancelRunningInvest(IvInvest $invest)
    {
        $refundAmount = 0;
        $refundType = request()->get('cancel-method', RefundType::PARTIAL);

        $remarks = request()->get('remarks');
        $note = request()->get('note');

        if (empty($note)) {
            throw ValidationException::withMessages(['note' => __("Cancelation note is required to cancelled.")]);
        }

        if ($refundType == RefundType::TOTAL) {
            $refundAmount = $invest->amount;
        } elseif ($refundType == RefundType::PARTIAL) {
            $earnedProfit = IvProfit::where('user_id', $invest->user_id)
                ->where('invest_id', $invest->id)
                ->sum('amount');
            $remainingAmount = BigDecimal::of($invest->amount)->minus(BigDecimal::of($earnedProfit));
            $refundAmount = $remainingAmount->toFloat() > 0 ? $remainingAmount->toFloat() : 0;
        }

        $data = [
            'ivx' => generate_unique_ivx(IvLedger::class, 'ivx'),
            'user_id' => $invest->user_id,
            'type' => LedgerTnxType::CAPITAL,
            'calc' => TransactionCalcType::NONE,
            'amount' => $refundAmount,
            'fees' => 0.00,
            'total' => to_sum($refundAmount, 0.00),
            'currency' => $invest->currency,
            'desc' => "Returned investment after cancelled",
            'note' => $note,
            'remarks' => $remarks,
            'invest_id' => $invest->id,
            'reference' => $invest->ivx,
            'source' => AccType('invest'),
            'created_at' => Carbon::now(),
        ];

        $ledger = new IvLedger();
        $ledger->fill($data);
        $ledger->save();

        $investWallet = get_user_account($invest->user_id, AccType('invest'));
        $investWallet->amount = BigDecimal::of($investWallet->amount)->plus($refundAmount);
        $investWallet->save();

        $invest->status = InvestmentStatus::CANCELLED;
        $invest->remarks = ($remarks) ? "New: ".$remarks ."\n\n". "Old:" .$invest->remarks : $invest->remarks;
        $invest->note = ($note) ? "New: ".$note ."\n\n". "Old:" .$invest->note : $invest->note;
        $invest->save();

        $this->saveIvAction(ActionType::REFUND, "invest", $invest->id);
        $this->saveIvAction(ActionType::STATUS_CANCEL, "invest", $invest->id);
        return $invest->fresh();
    }

    private function cancelByAdmin(IvInvest $invest)
    {
        if ($invest->status == InvestmentStatus::PENDING) {
            $invest = $this->cancelPendingInvest($invest);

            try {
                ProcessEmail::dispatch('investment-cancel-customer', data_get($invest, 'user'), null, $invest);
                ProcessEmail::dispatch('investment-cancel-admin', data_get($invest, 'user'), null, $invest);
            } catch (\Exception $e) {
                save_mailer_log($e, 'investment-cancel-admin');
            }

            return $invest;
        } else {
            $invest = $this->cancelRunningInvest($invest);

            try {
                ProcessEmail::dispatch('investment-cancellation-customer', data_get($invest, 'user'), null, $invest);
                ProcessEmail::dispatch('investment-cancellation-admin', data_get($invest, 'user'), null, $invest);
            } catch (\Exception $e) {
                save_mailer_log($e, 'investment-cancellation-admin');
            }

            return $invest;
        }
    }

    public function cancelInvestment(IvInvest $invest)
    {
        if (is_admin()) {
            return $this->cancelByAdmin($invest);
        } elseif ($invest->user_can_cancel && $invest->status == InvestmentStatus::PENDING) {
            $this->cancelPendingInvest($invest);
        } else {
            throw ValidationException::withMessages(['invest' => __("Cancellation failed! Please try again or contact us if the problem persists.")]);
        }
    }

}
