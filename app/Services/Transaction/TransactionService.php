<?php


namespace App\Services\Transaction;

use App\Enums\TransactionCalcType;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Enums\UserStatus;
use App\Models\Account;
use App\Models\Ledger;
use App\Models\Transaction;
use App\Services\Service;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class TransactionService extends Service
{
    private $transactionProcessor;
    private $rounded;

    public function __construct()
    {
        $this->transactionProcessor = new TransactionProcessor();

        $this->rounded = (object) [
            'fiat' => sys_settings('decimal_fiat_calc', 3),
            'crypto' => sys_settings('decimal_crypto_calc', 6)
        ];
    }

    /**
     * @param $amount
     * @param $exchangeRate
     * @return BigDecimal|\Brick\Math\BigNumber
     * @version 1.0.0
     * @since 1.0
     */
    private function toBase($amount, $exchangeRate)
    {
        return BigDecimal::of($amount)
            ->dividedBy(BigDecimal::of($exchangeRate), '6', RoundingMode::CEILING);
    }

    /**
     * @param (array) $data
     * @return array
     * @version 1.0.0
     * @since 1.0
     */
    private function toTnxMeta($data)
    {
        $except = ['method_name', 'currency_name', 'equal_amount', 'equal_currency', 'pay_from', 'pay_to', 'fx_rate', 'fx_currency', 'source', 'base_fees', 'amount_fees', 'exchange_rate', 'bonus_type', 'type', 'base_currency', 'currency', 'user_id', 'desc', 'note', 'remarks', 'reference'];

        if (is_array($data)) {
            return Arr::except($data, $except);
        }

        return $data;
    }

    /**
     * @param $tnxData
     * @return Transaction
     * @version 1.0.0
     * @since 1.0
     */
    public function createDepositTransaction($tnxData)
    {
        $userId = auth()->user()->id;
        $account = get_user_account($userId);
        $transaction = new Transaction();
        $transaction->tnx = generate_unique_tnx();
        $transaction->type = TransactionType::DEPOSIT;
        $transaction->user_id = $userId;
        $transaction->account_to = $account->id;
        $transaction->calc = TransactionCalcType::CREDIT;
        $transaction->amount = Arr::get($tnxData, 'base_amount');
        $transaction->fees = Arr::get($tnxData, 'base_fees', 0);
        $transaction->total = to_sum($transaction->amount, $transaction->fees);
        $transaction->currency = Arr::get($tnxData, 'base_currency');
        $transaction->tnx_amount = Arr::get($tnxData, 'amount');
        $transaction->tnx_fees = Arr::get($tnxData, 'amount_fees', 0);
        $transaction->tnx_total = to_sum($transaction->tnx_amount, $transaction->tnx_fees);
        $transaction->tnx_currency = Arr::get($tnxData, 'currency');
        $transaction->tnx_method = Arr::get($tnxData, 'method');
        $transaction->exchange = Arr::get($tnxData, 'exchange_rate');
        $transaction->status = TransactionStatus::NONE;
        $transaction->description = 'Deposit via ' . Arr::get($tnxData, 'method_name');
        $transaction->meta = $this->toTnxMeta($tnxData);
        $transaction->pay_to = Arr::get($tnxData, 'pay_to');
        $transaction->created_by = $userId;
        $transaction->save();

        return $transaction;
    }

    /**
     * @param $tnxData
     * @return Transaction
     * @version 1.1.4
     * @since 1.0
     */
    public function createManualTransaction($tnxData, $creator = null)
    {
        $userId = Arr::get($tnxData, 'user_id');
        $account = get_user_account($userId);
        $transaction = new Transaction();
        $transaction->tnx = generate_unique_tnx();
        $transaction->type = Arr::get($tnxData, 'type');
        $transaction->user_id = $userId;
        $transaction->calc = Arr::get($tnxData, 'calc');
        $transaction->amount = Arr::get($tnxData, 'base_amount');
        $transaction->fees = Arr::get($tnxData, 'base_fees', 0);
        $transaction->total = to_sum($transaction->amount, $transaction->fees);
        $transaction->currency = Arr::get($tnxData, 'base_currency');
        $transaction->tnx_amount = Arr::get($tnxData, 'amount');
        $transaction->tnx_fees = Arr::get($tnxData, 'amount_fees', 0);
        $transaction->tnx_total = to_sum($transaction->tnx_amount, $transaction->tnx_fees);
        $transaction->tnx_currency = Arr::get($tnxData, 'currency');
        $transaction->tnx_method = Arr::get($tnxData, 'method');
        $transaction->exchange = Arr::get($tnxData, 'exchange_rate', 1);
        $transaction->status = TransactionStatus::PENDING;
        $transaction->reference = Arr::get($tnxData, 'reference');
        $transaction->description = Arr::get($tnxData, 'desc');
        $transaction->remarks =  Arr::get($tnxData, 'remarks');
        $transaction->note = Arr::get($tnxData, 'note');
        $transaction->meta = $this->toTnxMeta($tnxData);
        $transaction->pay_to = Arr::get($tnxData, 'pay_to');

        if (!empty($creator) && isset($creator->id)) {
            $transaction->created_by = $creator->id;
        } elseif (auth()->check() && auth()->user()->id) {
            $transaction->created_by = auth()->user()->id;
        } else {
            $transaction->created_by = (!empty(system_admin())) ? system_admin()->id : 0;
        }

        if (Arr::get($tnxData, 'pay_from')) {
            $transaction->pay_from = Arr::get($tnxData, 'pay_from');
        }

        if (Arr::get($tnxData, 'calc') == TransactionCalcType::DEBIT) {
            $transaction->account_from = $account->id;
            if (Arr::get($tnxData, 'type') == TransactionType::TRANSFER) {
                $transaction->account_to = Arr::get($tnxData, 'account_to');
            }
        } else {
            if (Arr::get($tnxData, 'type') == TransactionType::TRANSFER) {
                $transaction->account_from = Arr::get($tnxData, 'account_from');
            }
            $transaction->account_to = $account->id;
        }
        $transaction->save();

        return $transaction;
    }

    /**
     * @param $tnxData
     * @return Transaction
     * @version 1.0.0
     * @since 1.0
     */
    public function createWithdrawTransaction($tnxData)
    {
        $userId = auth()->user()->id;
        $account = get_user_account($userId, data_get($tnxData, 'source'));

        $transaction = new Transaction();
        $transaction->tnx = generate_unique_tnx();
        $transaction->type = TransactionType::WITHDRAW;
        $transaction->user_id = $userId;
        $transaction->account_from = $account->id;
        $transaction->calc = TransactionCalcType::DEBIT;
        $transaction->amount = Arr::get($tnxData, 'base_amount');
        $transaction->fees = Arr::get($tnxData, 'base_fees', 0);
        $transaction->total = to_sum($transaction->amount, $transaction->fees);
        $transaction->currency = Arr::get($tnxData, 'base_currency');
        $transaction->tnx_amount = Arr::get($tnxData, 'amount');
        $transaction->tnx_fees = Arr::get($tnxData, 'amount_fees', 0);
        $transaction->tnx_total = to_sum($transaction->tnx_amount, $transaction->tnx_fees);
        $transaction->tnx_currency = Arr::get($tnxData, 'currency');
        $transaction->tnx_method = Arr::get($tnxData, 'method');
        $transaction->exchange = Arr::get($tnxData, 'exchange_rate');
        $transaction->status = TransactionStatus::PENDING;
        $transaction->description = 'Withdraw via ' . Arr::get($tnxData, 'method_name');
        $transaction->meta = $this->toTnxMeta($tnxData);
        $transaction->pay_to = Arr::get($tnxData, 'pay_to');
        $transaction->created_by = $userId;
        $transaction->save();

        return $transaction;
    }


    /**
     * @param $transaction
     * @param $ledgerBalance
     * @return Ledger
     * @throws \Exception
     * @version 1.0.0
     * @since 1.0
     */
    private function createLedgerEntry($transaction, $ledgerBalance)
    {
        $ledger = new Ledger();
        $ledger->transaction_id = $transaction->id;

        if ($transaction->calc == TransactionCalcType::DEBIT) {
            $ledger->debit = $transaction->amount;
            $ledger->account_id = $transaction->account_from;
            $balance = BigDecimal::of($ledgerBalance)->minus(BigDecimal::of($transaction->total));
        }

        if ($transaction->calc == TransactionCalcType::CREDIT) {
            $ledger->credit = $transaction->total;
            $ledger->account_id = $transaction->account_to;
            $balance = BigDecimal::of($ledgerBalance)->plus(BigDecimal::of($transaction->amount));
        }

        if ($balance < BigDecimal::of(0.00)) {
            throw new \Exception(__("Unprocessable transaction."));
        }

        $ledger->balance = $balance;
        $ledger->save();

        return $ledger;
    }

    private function getLedgerBalance($accountId)
    {
        $latestLedgerEntry = Ledger::where('account_id', $accountId)->orderBy('id', 'desc')->first();
        return data_get($latestLedgerEntry, 'balance', 0.00);
    }

    /**
     * @param $transaction
     * @param array $completedBy
     * @return mixed
     * @throws \Exception
     * @version 1.0.0
     * @since 1.0
     */
    public function confirmTransaction($transaction, $completedBy = [], $auto = false)
    {
        if (in_array($transaction->status, [TransactionStatus::COMPLETED])) {
            throw new \Exception(__("Transaction already completed."));
        }

        if (!in_array($transaction->status, [
            TransactionStatus::PENDING,
            TransactionStatus::ONHOLD,
            TransactionStatus::CONFIRMED
        ])) {
            throw new \Exception(__("Invalid transaction status."));
        }

        $customerStatus = data_get($transaction, 'customer.status');
        if ($customerStatus != UserStatus::ACTIVE) {
            throw new \Exception(__("Invalid Customer Status."));
        }

        $userAccount = get_user_account($transaction->user_id);
        $ledgerBalance = $this->getLedgerBalance($userAccount->id);
        $this->createLedgerEntry($transaction, $ledgerBalance);

        if ($transaction->calc == TransactionCalcType::CREDIT) {
            $userAccount->amount = BigDecimal::of($userAccount->amount)->plus(BigDecimal::of($transaction->amount));
            $userAccount->save();
        }

        $transaction->status = TransactionStatus::COMPLETED;
        $transaction->completed_at = Carbon::now();
        $transaction->completed_by = $completedBy;
        $transaction->save();
        $transaction->fresh();

        if (has_deposit_bonus()) {
            $this->addDepositBonus($transaction);
        }

        if (referral_system()) {
            $this->addReferralCommissionDeposit($transaction);
        }

        return $userAccount->toArray();
    }

    /**
     * @param $reference
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    public function getTransactionByReference($reference)
    {
        return Transaction::where('reference', $reference)->first();
    }

    private function addDepositBonus($transaction)
    {
        if ($transaction->type != TransactionType::DEPOSIT) {
            return true;
        }
        if ($transaction->status != TransactionStatus::COMPLETED) {
            return true;
        }

        $tnxCount = Transaction::where('user_id', $transaction->user_id)
            ->where('type', TransactionType::DEPOSIT)
            ->where('status', TransactionStatus::COMPLETED)
            ->count();

        if ($tnxCount > 1) {
            return true;
        }

        $currency = base_currency();
        $bonus = deposit_bonus($transaction->amount);
        if (empty($bonus)) {
            return true;
        }

        $tnxData = [
            'type' => TransactionType::BONUS,
            'calc' => TransactionCalcType::CREDIT,
            'base_amount' => $bonus,
            'base_currency' => $currency,
            'amount' => $bonus,
            'currency' => $currency,
            'method' => 'system',
            'desc' => "Bonus for First Deposit",
            'user_id' => $transaction->user_id,
            'reference' => $transaction->tnx
        ];

        $completedBy = (!empty(system_admin())) ? ['id' => system_admin()->id, 'name' => system_admin()->name] : [];
        $createBy = (!empty(system_admin())) ? system_admin() : false;

        $bonusTransaction = $this->createManualTransaction($tnxData, $createBy);
        $this->confirmTransaction($bonusTransaction, $completedBy);

        return true;
    }

    public function addSignupBonus($user)
    {
        if (empty($user)) {
            return true;
        }
        if (!in_array($user->meta('registration_method'), ['email', 'social'])) {
            return true;
        }

        if (referral_system() && allow_bonus_joined('signup') && $user->has_valid_referrer && gss('referral_signup_user_reward', 'no') == 'yes') {
            return true;
        }

        $currency = base_currency();
        $amount = signup_bonus('amount');
        if (empty($amount)) {
            return true;
        }

        $tnxData = [
            'type' => TransactionType::BONUS,
            'calc' => TransactionCalcType::CREDIT,
            'base_amount' => $amount,
            'base_currency' => $currency,
            'amount' => $amount,
            'currency' => $currency,
            'method' => 'system',
            'desc' => "Signup Bonus",
            'user_id' => $user->id
        ];

        $completedBy = (!empty(system_admin())) ? ['id' => system_admin()->id, 'name' => system_admin()->name] : [];
        $createBy = (!empty(system_admin())) ? system_admin() : false;

        $signupTransaction = $this->createManualTransaction($tnxData, $createBy);
        $this->confirmTransaction($signupTransaction, $completedBy);

        return true;
    }


    public function addReferralCommission($user)
    {
        if (empty($user) || !$user->has_valid_referrer) {
            return true;
        }

        $currency = base_currency();
        $createdBy = (!empty(system_admin())) ? system_admin() : false;

        $tnxData = [
            'type' => TransactionType::REFERRAL,
            'calc' => TransactionCalcType::CREDIT,
            'base_currency' => $currency,
            'currency' => $currency,
            'method' => 'system',
        ];

        $refererBonus = referral_bonus_referer('signup', 0);
        if (allow_bonus_referer('signup') && $refererBonus > 0) {
            $tnxData['base_amount'] = $tnxData['amount'] = $refererBonus;
            $tnxData['user_id'] = $user->refer;
            $tnxData['desc'] = "Commission for Referral Signup";

            $tnxData['referral'] = [
                'level' => 'lv1',
                'bonus' =>  gss('referral_signup_referer_bonus', 0),
                'calc' => 'fixed',
                'user' => $user->id,
                'type' => 'refer',
                'action' => 'signup',
            ];

            $this->createManualTransaction($tnxData, $createdBy);
        }

        $userBonus = referral_bonus_joined('signup', 0);
        if (allow_bonus_joined('signup') && $userBonus > 0) {
            $tnxData['base_amount'] = $tnxData['amount'] = $userBonus;
            $tnxData['user_id'] = $user->id;
            $tnxData['desc'] = "Commission for Referral Join";

            $tnxData['referral'] = [
                'level' => 'lv0',
                'bonus' =>  gss('referral_signup_user_bonus', 0),
                'calc' => 'fixed',
                'user' => $user->refer,
                'type' => 'join',
                'action' => 'signup',
            ];

            $this->createManualTransaction($tnxData, $createdBy);
        }

        return true;
    }

    public function addReferralCommissionDeposit($transaction)
    {
        if ($transaction->type != TransactionType::DEPOSIT) {
            return true;
        }

        $tnxCount = Transaction::where('user_id', $transaction->user_id)
            ->where('type', TransactionType::DEPOSIT)
            ->where('status', TransactionStatus::COMPLETED)
            ->count();

        $createdBy = (!empty(system_admin())) ? system_admin() : false;
        $currency = base_currency();

        $tnxData = [
            'type' => TransactionType::REFERRAL,
            'calc' => TransactionCalcType::CREDIT,
            'base_currency' => $currency,
            'currency' => $currency,
            'method' => 'system',
            'desc' => "Referral Deposit Bonus",
            'reference' => $transaction->tnx,
        ];

        $refererBonus = referral_bonus_referer('deposit', $transaction->amount);
        if (allow_bonus_referer('deposit') && $refererBonus > 0) {

            if (!$transaction->customer->has_valid_referrer) {
                return;
            }

            if (validate_bonus_condition('referer', $tnxCount) === false) {
                return;
            }

            $tnxData['base_amount'] = $tnxData['amount'] = $refererBonus;
            $tnxData['user_id'] = $transaction->customer->refer;

            $tnxData['referral'] = [
                'level' => 'lv1',
                'bonus' => gss('referral_deposit_referer_bonus', 0),
                'calc' => gss('referral_deposit_referer_type', 'percent'),
                'user' => $transaction->user_id,
                'type' => 'refer',
                'action' => 'deposit',
                'tnx_id' => $transaction->id,
                'tnx_amount' => $transaction->amount,
            ];

            $this->createManualTransaction($tnxData, $createdBy);
        }

        $userBonus = referral_bonus_joined('deposit', $transaction->amount);
        if (allow_bonus_joined('deposit') && $userBonus > 0) {

            if (blank($transaction, 'customer.referrer')) {
                return;
            }

            if (validate_bonus_condition('user', $tnxCount) === false) {
                return;
            }

            $tnxData['base_amount'] = $tnxData['amount'] = $userBonus;
            $tnxData['user_id'] = $transaction->user_id;

            $tnxData['referral'] = [
                'level' => 'lv0',
                'bonus' => gss('referral_deposit_user_bonus', 0),
                'calc' => gss('referral_deposit_user_type', 'fixed'),
                'user' => $transaction->customer->refer,
                'type' => 'join',
                'action' => 'deposit',
                'tnx_id' => $transaction->id,
                'tnx_amount' => $transaction->amount,
            ];

            $this->createManualTransaction($tnxData, $createdBy);
        }

        return true;
    }
}
