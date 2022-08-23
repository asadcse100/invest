<?php


namespace App\Services\Investment;

use App\Models\User;
use App\Models\IvLedger;

use App\Enums\LedgerTnxType;
use App\Enums\TransactionType;
use App\Enums\TransactionCalcType;

use App\Traits\WrapInTransaction;
use App\Services\Transaction\TransactionService;

use Brick\Math\BigDecimal;

class IvBalanceTransferService
{
    use WrapInTransaction;

    private $user;
    private $user_id;

    public function setUser(User $user): self
    {
        $this->user = $user;
        $this->user_id = $user->id;

        return $this;
    }

    public function autoTransfer()
    {
        $defaultMin = gss('iv_min_transfer', 0.001);
        $setting = $this->user->auto_transfer_settings;
        if ($setting === true ) {
            $getUserMin = user_meta('setting_min_transfer', null, $this->user) ?? 0;
            $min = (BigDecimal::of($getUserMin)->compareTo($defaultMin) > 0) ? $getUserMin : $defaultMin;
            
            $userAccount = get_user_account($this->user_id, AccType('invest'));
            $transferAmount = data_get($userAccount, 'amount');
            
            if (BigDecimal::of($transferAmount)->compareTo(0) == 1 && BigDecimal::of($transferAmount)->compareTo($min) >= 0) {
                $this->processTransfer($userAccount, $transferAmount, 'auto');
            }
        }
    }

    public function manualTransfer($amount)
    {
        if (is_null($this->user) || is_null($this->user_id)) {
            throw new \Exception(__("Invalid transfer transaction."));
        }

        $account = get_user_account($this->user_id, AccType('invest'));
        $balance = data_get($account, 'amount');

        if (BigDecimal::of($amount)->compareTo(0) > 0 
            && BigDecimal::of($balance)->compareTo(0) > 0 
            && BigDecimal::of($amount)->compareTo($balance) < 1) {
                return $this->processTransfer($account, $amount, 'manual');
        }

        return false;
    }

    private function processTransfer($account, $amount, $mode)
    {
        if (is_null($this->user) || is_null($this->user_id)) {
            throw new \Exception(__("Invalid transfer transaction."));
        }

        $tnxData = [
            'user_id' => $this->user_id,
            'base_amount' => $amount,
            'amount' => $amount,
            'calc' => TransactionCalcType::CREDIT,
            'type' => TransactionType::INVESTMENT,
            'base_currency' => base_currency(),
            'currency' => base_currency(),
            'base_fees' => 0,
            'amount_fees' => 0,
            'exchange_rate' => 1,
            'method' => 'system',
            'desc' => 'Received from Investment Account',
            'pay_to' => AccType('main'),
            'pay_from' => AccType('invest'),
            'transfer' => ($mode == 'auto') ? 'auto' : 'manual'
        ];

        return $this->wrapInTransaction(function($tnxData, $account) {
            $transactionService = new TransactionService();
            $transaction = $transactionService->createManualTransaction($tnxData);
            $ledger = $this->makeTransferIvLedger($transaction);

            $transaction->reference = $ledger->ivx;
            $transaction->save();

            $completedBy = (!empty(system_admin())) ? ['id' => system_admin()->id, 'name' => system_admin()->name] : [];
            $transactionService->confirmTransaction($transaction, $completedBy);

            $account->amount = to_minus($account->amount, $ledger->amount);
            $account->save();

            return $ledger->fresh();
        }, $tnxData, $account);
    }

    private function makeTransferIvLedger($transaction)
    {
        $data = [
            'ivx' => generate_unique_ivx(IvLedger::class, 'ivx'),
            'user_id' => $transaction->user_id,
            'calc' =>  TransactionCalcType::DEBIT,
            'type' => LedgerTnxType::TRANSFER,
            'amount' => $transaction->amount,
            'fees' => 0.0,
            'total' => to_sum($transaction->amount, 0.0),
            'currency' => $transaction->currency,
            'invest_id' => 0,
            'tnx_id' => $transaction->id,
            'reference' => $transaction->tnx,
            'source'=> $transaction->pay_from,
            'dest'=> $transaction->pay_to,
            'desc' => 'Transferred to Main Account',
            'meta' => json_encode(['mode' => data_get($transaction, 'meta.transfer')])
        ];

        $ledger = new IvLedger();
        $ledger->fill($data);
        $ledger->save();

        return $ledger;
    }
}
