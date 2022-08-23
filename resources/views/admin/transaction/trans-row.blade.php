@php
use \App\Enums\TransactionStatus as TStatus;
use \App\Enums\TransactionType as TType;
use \App\Enums\TransactionCalcType as TCType;

$base_currency = base_currency();

@endphp
<div class="nk-tb-col">
    <div class="nk-tnx-type">
        <div class="nk-tnx-type-badge">
            {!! tnx_type_icon($transaction, 'tnx-type-icon') !!}
        </div>
        <div class="nk-tnx-type-text">
            <span class="tb-lead">
                @if($transaction->type == TType::TRANSFER)
                    {{ ($transaction->calc == TCType::DEBIT) ? __("Send Funds") : __("Receive Funds") }}
                @else
                    {{ $transaction->type_of_fund }}
                @endif
            </span>
            <span class="nk-tnx-meta">
                <span class="date">{{ show_date($transaction->created_at) }}</span>
                <span class="status dot-join{{ ($transaction->status==TStatus::CANCELLED) ? ' text-danger' : '' }}">
                    {{ ($transaction->type == TType::INVESTMENT && in_array($transaction->status, [TStatus::PENDING])) ? __("Locked") : __(ucfirst($transaction->status)) }}
                </span>
            </span>
        </div>
    </div>
</div>
<div class="nk-tb-col tb-col-sm">
    <span class="tb-lead-sub">
        {{ the_uid($transaction->customer->id) }} 
        <em class="icon ni ni-info text-soft fs-13px" data-toggle="tooltip" title="{{ $transaction->customer->name }} ({{ str_protect($transaction->customer->email) }})"></em>
    </span>
    <span class="tb-sub">{{ __('via :Method', ['method' => $transaction->method_name]) }}</span>
</div>
<div class="nk-tb-col tb-col-lg">
    <span class="tb-lead-sub">{{ data_get($transaction, 'tnx') }}</span>
    <span class="badge badge-dot {{ data_get($transaction, 'type') == TType::DEPOSIT ? 'badge-success' : 'badge-warning' }}">
        {{ ucfirst(__(data_get($transaction, 'type'))) }}
    </span>
</div>
<div class="nk-tb-col text-right">
    @if ($transaction->calc == TCType::CREDIT) 
        <span class="tb-amount">+ {{ amount_z(data_get($transaction, 'tnx_amount'), data_get($transaction, 'tnx_currency'), ['dp' => 'calc']) }} <span>{{ data_get($transaction, 'tnx_currency') }}</span></span>
        <span class="tb-amount-sm">{{ amount_z(data_get($transaction, 'amount'), $base_currency, ['dp' => 'calc']) }} {{ $base_currency }}</span>
    @else 
        <span class="tb-amount">- {{ amount_z(data_get($transaction, 'tnx_total'), data_get($transaction, 'tnx_currency'), ['dp' => 'calc']) }} 
            <span>{{ data_get($transaction, 'tnx_currency') }}</span>
        </span>
        <span class="tb-amount-sm">{{ amount_z(data_get($transaction, 'total'), $base_currency, ['dp' => 'calc']) }} {{ $base_currency }}</span>
    @endif
</div>
<div class="nk-tb-col nk-tb-col-tools">
    <ul class="nk-tb-actions gx-2">

        @if (in_array($transaction->status, [TStatus::PENDING, TStatus::ONHOLD, TStatus::CONFIRMED]) && $transaction->type != TType::INVESTMENT)
        <li class="nk-tb-action-hidden">
            <a href="javascript:void(0)" class="btn btn-sm btn-trigger btn-icon m-tnx-update btn-tooltip" 
                data-uid="{{ the_hash($transaction->id) }}" data-tnx="{{ $transaction->tnx }}" data-action="reject"
                data-state="{{ TStatus::CANCELLED }}" title="{{ __('Reject') }}"><em class="icon ni ni-cross-fill-c"></em></a>
        </li>
        @endif

        @if( ($transaction->type == TType::DEPOSIT || $transaction->type == TType::TRANSFER) && in_array($transaction->status, [TStatus::PENDING, TStatus::ONHOLD]) && $transaction->is_online == false)
        <li class="nk-tb-action-hidden">
            <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-trigger m-tnx-update btn-tooltip" 
                data-uid="{{ the_hash($transaction->id) }}" data-tnx="{{ $transaction->tnx }}" 
                data-action="approve"
                data-state="{{ TStatus::COMPLETED }}" title="{{ __('Approve') }}"><em class="icon ni ni-check-fill-c"></em></a>
        </li>
        @endif

        @if( $transaction->type == TType::WITHDRAW && in_array($transaction->status, [TStatus::PENDING, TStatus::CONFIRMED]) )
        <li class="nk-tb-action-hidden">
            <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-trigger m-tnx-update btn-tooltip" 
                data-uid="{{ the_hash($transaction->id) }}" data-tnx="{{ $transaction->tnx }}" 
                data-action="{{ (($transaction->status == TStatus::CONFIRMED) ? 'approve' : 'confirm') }}"
                data-state="{{ (($transaction->status == TStatus::CONFIRMED) ? TStatus::COMPLETED : TStatus::CONFIRMED) }}" 
                title="{{ (($transaction->status == TStatus::CONFIRMED) ? __('Complete') : __('Confirm')) }}"><em class="icon ni ni-check-fill-c"></em></a>
        </li>
        @endif

        @if( $transaction->type == TType::REFERRAL && in_array($transaction->status, [TStatus::PENDING]))
        <li class="nk-tb-action-hidden">
            <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-trigger m-tnx-update btn-tooltip" 
                data-uid="{{ the_hash($transaction->id) }}" data-tnx="{{ $transaction->tnx }}" 
                data-action="approve"
                data-state="{{ TStatus::COMPLETED }}" title="{{ __('Approve') }}"><em class="icon ni ni-check-fill-c"></em></a>
        </li>
        @endif

        <li class="nk-tb-action-hidden">
            <a href="javascript:void(0)" class="btn btn-sm btn-trigger btn-icon m-tnx-view btn-tooltip" data-action="view" data-view="tnx" 
            data-uid="{{ the_hash($transaction->id) }}" title="{{ __('Details') }}"><em class="icon ni ni-eye-fill"></em></a>
        </li>

        <li>
            <div class="dropdown">
                <a href="#" class="dropdown-toggle btn btn-sm btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <ul class="link-list-opt no-bdr">
                        <li><a href="javascript:void(0)" class="m-tnx-view" data-action="view" data-view="tnx" data-uid="{{ the_hash($transaction->id) }}"><em class="icon ni ni-eye"></em><span>{{ __('View Details') }}</span></a></li>
                        <li><a href="javascript:void(0)" class="m-tnx-view" data-action="view" data-view="profile" data-uid="{{ the_hash($transaction->id) }}"><em class="icon ni ni-user-alt"></em><span>{{ __('User Profile') }}</span></a></li>

                        @if ( in_array($transaction->status, [TStatus::PENDING, TStatus::ONHOLD, TStatus::CONFIRMED]) )

                        @if($transaction->type != TType::INVESTMENT)
                        <li class="divider"></li>
                        @endif

                        @if( $transaction->type == TType::WITHDRAW && in_array($transaction->status, [TStatus::PENDING, TStatus::CONFIRMED]) )
                        <li><a class="m-tnx-update" href="javascript:void(0)" data-uid="{{ the_hash($transaction->id) }}" data-tnx="{{ $transaction->tnx }}" 
                            data-action="{{ (($transaction->status == TStatus::CONFIRMED) ? 'approve' : 'confirm') }}" 
                            data-state="{{ (($transaction->status == TStatus::CONFIRMED) ? TStatus::COMPLETED : TStatus::CONFIRMED) }}">
                            <em class="icon ni ni-check-circle-cut"></em><span>{{ (($transaction->status == TStatus::CONFIRMED) ? __('Complete') : __('Confirm')) }}</span></a>
                        </li>
                        @endif

                        @if( $transaction->type == TType::DEPOSIT && in_array($transaction->status, [TStatus::PENDING, TStatus::ONHOLD]) )
                        <li><a class="m-tnx-update" href="javascript:void(0)" data-uid="{{ the_hash($transaction->id) }}" data-tnx="{{ $transaction->tnx }}" 
                            data-action="{{ (($transaction->is_online) ? 'online' : 'approve') }}" 
                            data-state="{{ (($transaction->is_online) ? 'check' : TStatus::COMPLETED) }}">
                            @if($transaction->is_online)
                            <em class="icon ni ni-reload"></em><span>{{ __('Check Status') }}</span></a>
                            @else
                            <em class="icon ni ni-check-c"></em><span>{{ __('Approve') }}</span></a>
                            @endif
                        </li>
                        @endif

                        @if( $transaction->type == TType::REFERRAL && in_array($transaction->status, [TStatus::PENDING]) )
                        <li><a class="m-tnx-update" href="javascript:void(0)" data-uid="{{ the_hash($transaction->id) }}" data-tnx="{{ $transaction->tnx }}" 
                            data-action="approve" 
                            data-state="{{ TStatus::COMPLETED }}">
                            <em class="icon ni ni-check-c"></em><span>{{ __('Approve') }}</span></a>
                        </li>
                        @endif

                        @if( $transaction->type != TType::INVESTMENT )
                        <li><a class="m-tnx-update" href="javascript:void(0)" data-uid="{{ the_hash($transaction->id) }}" data-tnx="{{ $transaction->tnx }}" data-action="reject" data-state="{{ TStatus::CANCELLED }}">
                            <em class="icon ni ni-cross-c"></em><span>{{ __('Reject') }}</span></a>
                        </li>
                        @endif

                        @endif
                    </ul>
                </div>
            </div>
        </li>
    </ul>
</div>
