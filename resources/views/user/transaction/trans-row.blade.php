@php

use \App\Enums\TransactionCalcType;
use \App\Enums\TransactionStatus;
use \App\Enums\TransactionType;

$base_currency = base_currency();

@endphp

<div class="nk-odr-item {{ $transaction->status == TransactionStatus::CANCELLED ? 'is-cancelled' : '' }}">
    <div class="nk-odr-col">
        <div class="nk-odr-info">
            <div class="nk-odr-badge">
                {!! tnx_type_icon($transaction, 'odr-icon') !!}
            </div>
            <div class="nk-odr-data">
                <div class="nk-odr-label">
                    <strong class="ellipsis">
                        @if($transaction->type == TransactionType::TRANSFER)
                            {{ ($transaction->calc == TransactionCalcType::DEBIT) ? __("Send Funds") : __("Receive Funds") }}
                        @else
                            {{ __(trans_replace($transaction->description)) }}
                        @endif
                    </strong>
                </div>
                <div class="nk-odr-meta">
                    <span class="date">{{ ($transaction->status == TransactionStatus::COMPLETED) ? show_date($transaction->completed_at) : show_date($transaction->created_at) }}</span>
                    <span class="status dot-join{{ $transaction->status == TransactionStatus::CANCELLED ? ' text-danger' : '' }}">
                        {{ __(data_get($transaction->details, 'status')) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="nk-odr-col nk-odr-col-amount">
        <div class="nk-odr-amount">
            <div class="number-md text-s {{ $transaction->calc == TransactionCalcType::CREDIT ? 'text-success' : 'text-danger' }}">
                {{ ($transaction->calc == TransactionCalcType::CREDIT) ? '+' : '-' }} {{ ($transaction->calc == TransactionCalcType::CREDIT) ? amount_z($transaction->amount, $base_currency, ['dp' => 'calc']): amount_z($transaction->total, $base_currency, ['dp' => 'calc']) }}
                <span class="currency">{{ $base_currency }}</span>
            </div>
            <div class="number-sm">{{ ($transaction->calc == TransactionCalcType::CREDIT) ? amount_z($transaction->tnx_amount, $transaction->tnx_currency, ['dp' => 'calc']) : amount_z($transaction->tnx_total, $transaction->tnx_currency, ['dp' => 'calc']) }} <span class="currency">{{ $transaction->tnx_currency }}</span></div>
        </div>
    </div>
    <div class="nk-odr-col nk-odr-col-action">
        <div class="nk-odr-action">
            <a class="tnx-details" href="javascript:void(0)" data-tnx="{{ the_hash($transaction->id) }}"><em class="icon ni ni-forward-ios"></em></a>
        </div>
    </div>
</div>
