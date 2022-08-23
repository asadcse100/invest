@php

use \App\Enums\TransactionType;
use \App\Enums\TransactionStatus;
use \App\Enums\TransactionCalcType;

$details = data_get($transaction, 'details');
$type  = data_get($transaction, 'type');
$calc  = data_get($transaction, 'calc');
$status  = data_get($transaction, 'status');
$ledger = data_get($transaction,'ledger');

@endphp
<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
        <div class="modal-body modal-body-md">
            <div class="nk-modal-head mb-2 mb-sm-4">
                <h4 class="nk-modal-title title">
                    {!! __('Referral Earn :orderid', ['orderid' => '<small class="text-primary">/ '.data_get($details, 'order_id').'</small>' ]) !!}
                </h4>
            </div>
            <div class="nk-block">
                <div class="nk-block-between flex-wrap g-3">
                    <div class="nk-tnx">
                        {!! tnx_type_icon($transaction, 'tnx-icon') !!}
                        <div class="nk-tnx-text">
                            <h5 class="title">{{ data_get($details, 'amount') }}</h5>
                            <span class="sub-text mt-n1">{{ data_get($details, 'order_date') }}</span>
                        </div>
                    </div>
                    <ul class="align-center flex-wrap gx-3">
                        <li><span class="badge badge-sm{{ css_state_tnx($transaction->status, 'badge') }}">{{ __(ucfirst(tnx_status_switch(data_get($details, 'status')))) }}</span></li>
                    </ul>
                </div>

                <div class="divider md stretched"></div>
                <h5 class="overline-title">{{ __('Commission Details') }}</h5>
                <div class="row gy-3">
                    <div class="col-md-6">
                        <span class="sub-text">{{ __('Amount') }}</span>
                        <span class="caption-text">{{ data_get($details, 'tnx_amount') }}</span>
                    </div>
                    <div class="col-md-6">
                        <span class="sub-text">{{ __('Commission') }}</span>
                        @if (data_get($transaction, 'meta.referral.calc') == 'fixed')
                        <span class="caption-text">{{ __("Flat amount on :type", ['type' => data_get($transaction, 'meta.referral.action')]) }}</span>
                        @else
                        <span class="caption-text">{{ __(":amount on :type", ['type' => data_get($transaction, 'meta.referral.action'), 'amount' => data_get($transaction, 'meta.referral.bonus')."%"]) }}</span>
                        @endif
                    </div>

                    @if (!empty(get_user(data_get($transaction, 'meta.referral.user'))))
                    <div class="col-md-6">
                        <span class="sub-text">{{ __('Related to Account') }}</span>
                        <span class="caption-text">{{ str_compact(get_user(data_get($transaction, 'meta.referral.user'))->username, '..', 3) }}</span>
                    </div>
                    @endif

                    <div class="col-md-6">
                        <span class="sub-text">{{ __('Date') }}</span>
                        <span class="caption-text text-break">{{ show_date(data_get($transaction, 'created_at'), true) }}</span>
                    </div>

                    <div class="col-lg-6">
                        <span class="sub-text">{{ __('Details') }}</span>
                        <span class="caption-text">{{ data_get($details, 'details') }}</span>
                    </div>
                </div>

                <div class="divider md stretched"></div>
                <h5 class="overline-title">{{ __('Additional') }}</h5>
                <div class="row gy-3">

                    @if($status == TransactionStatus::COMPLETED)
                    <div class="col-lg-6">
                        <span class="sub-text">{{ __('Paid At') }}</span>
                        <span class="caption-text text-break">{{ show_date(data_get($transaction, 'completed_at'), true) }}</span>
                    </div>
                    @endif

                    @if(data_get($ledger, 'balance'))
                    <div class="col-lg-6">
                        <span class="sub-text">{{ __('Updated Balance') }}</span>
                        <span class="caption-text">{{ money(data_get($ledger, 'balance'), base_currency()) }}</span>
                    </div>
                    @endif

                    @if(data_get($details, 'notes'))
                    <div class="col-lg-12">
                        <span class="sub-text">{{ __('Notes') }}</span>
                        <span class="caption-text">{{ data_get($details, 'notes') }}</span>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
