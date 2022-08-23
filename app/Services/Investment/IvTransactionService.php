<?php

namespace App\Services\Investment;

use App\Enums\AccountBalanceType;
use App\Enums\CurrencyType;
use App\Enums\LedgerTnxType;
use App\Enums\TransactionCalcType;
use App\Models\IvLedger;
use Brick\Math\BigDecimal;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class IvTransactionService
{

    public function createTnxData()
    {
        $this->tnxData = [];
    }

    private function toIvTnxMeta($data)
    {
        $except = ['user_id', 'type', 'calc', 'amount', 'desc', 'remarks', 'note'];

        if (is_array($data)) {
            return Arr::except($data, $except);
        }

        return $data;
    }

    public function create(array $tnxData)
    {
        $data = [
            'ivx' => generate_unique_ivx(IvLedger::class, 'ivx'),
            'user_id' => Arr::get($tnxData, 'user_id'),
            'type' => Arr::get($tnxData, 'type'),
            'calc' => Arr::get($tnxData, 'calc'),
            'amount' => Arr::get($tnxData, 'amount', 0),
            'fees' => 0,
            'total' => Arr::get($tnxData, 'amount', 0),
            'currency' => base_currency(),
            'desc' =>  Arr::get($tnxData, 'desc'),
            'remarks' =>  Arr::get($tnxData, 'remarks'),
            'note' =>  Arr::get($tnxData, 'note'),
            'invest_id' => 0,
            'transaction_id' => 0,
            'reference' => 0,
            'meta' => (!empty($this->toIvTnxMeta($tnxData))) ? json_encode($this->toIvTnxMeta($tnxData)) : null,
            'source' => Arr::get($tnxData, 'calc') === TransactionCalcType::DEBIT ? AccountBalanceType::INVEST : null,
            'dest' => Arr::get($tnxData, 'calc') === TransactionCalcType::CREDIT ? AccountBalanceType::INVEST : null,
            'created_at' => Carbon::now(),
        ];

        $ledger = new IvLedger();
        $ledger->fill($data);
        $ledger->save();

        $userAccount = get_user_account($ledger->user_id, AccountBalanceType::INVEST);

        if ($ledger->calc == TransactionCalcType::CREDIT) {
            $userAccount->amount = BigDecimal::of($userAccount->amount)->plus(BigDecimal::of($ledger->amount));
            $userAccount->save();
        }

        if ($ledger->calc == TransactionCalcType::DEBIT) {
            $userAccount->amount = BigDecimal::of($userAccount->amount)->minus(BigDecimal::of($ledger->amount));
            $userAccount->save();
        }

        return;
    }
}
