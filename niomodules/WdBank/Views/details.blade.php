@php
    $details = data_get($transaction, 'details');
@endphp
<div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
        <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
        <div class="modal-body modal-body-md">
            <div class="nk-pps-apps">
                <div class="nk-pps-title text-center">
                    <h3 class="title">{{ __('Confirm Your Withdrawal') }}</h3>
                    <p class="caption-text">{!! __('You are about to withdraw :amount at :account.', [
                        'amount' => '<strong>'.data_get($details, 'tnx_amount').'</strong>',
                        'account' => '<strong>'.data_get($transaction, 'meta.account').'</strong> ('.data_get($wdm, 'name').')' ]) !!}
                    </p>
                </div>
                <div class="nk-pps-data">
                    <ul class="nk-olist">
                        <li class="nk-olist-item">
                            <div class="label lead-text">{{ __('Withdraw Account (:method)', ['method' => data_get($wdm, 'name') ]) }}</div>
                            <div class="data"><span class="method"><em class="icon ni ni-building-fill"></em> <span class="ellipsis w-max-225px">{{ data_get($transaction, 'meta.account') }}</span></span></div>
                        </li>

                        <li class="nk-olist-item {{ (data_get($details, 'tnx_fees')) ? ' is-grouped' : '' }}">
                            <div class="label lead-text">{{ __('Amount to withdraw') }}</div>
                            <div class="data"><span class="amount">{{ data_get($details, 'tnx_total') }}</span></div>
                        </li>

                        @if (data_get($details, 'tnx_fees'))
                        <li class="nk-olist-item small">
                            <div class="label">{{ __('Processing fee') }}</div>
                            <div class="data"><span class="amount">{{ data_get($details, 'tnx_fees') }}</span></div>
                        </li>
                        @endif

                        <li class="nk-olist-item{{ (data_get($details, 'currency') != data_get($details, 'tnx_currency')) ? ' is-grouped' : '' }}">
                            <div class="label lead-text">
                                {{ __('Total amount to debit') }} 
                                <em class="icon ni ni-info small text-soft nk-tooltip" title="{{ __("The amount will be deducted from your main balance.") }}"></em>
                            </div>
                            <div class="data"><span class="amount">{{ data_get($details, 'total') }}</span></div>
                        </li>

                        @if (data_get($details, 'currency') != data_get($details, 'tnx_currency'))
                        <li class="nk-olist-item small">
                            <div class="label">{{ __('Exchange rate') }}</em></div>
                            <div class="data fw-normal text-soft">
                                <span class="amount">{{ __(':amount = :rate', ['amount' => '1'.' '.data_get($details, 'currency'), 'rate' => data_get($details, 'exchange_rate')]) }}</span>
                            </div>
                        </li>
                        @endif
                    </ul>

                    @if(data_get($transaction, 'meta.desc'))
                        <ul class="nk-olist">
                            <li class="nk-olist-item">
                                <div class="label">{{ __('Description') }}</div>
                                <div class="data note">{{ data_get($transaction, 'meta.desc') }}</div>
                            </li>
                        </ul>
                    @endif

                    @if(data_get($transaction, 'reference'))
                        <ul class="nk-olist">
                            <li class="nk-olist-item">
                                <div class="label">{{ __('Reference') }}</div>
                                <div class="data note">{{ data_get($transaction, 'reference') }}</div>
                            </li>
                        </ul>
                    @endif

                    <ul class="nk-olist">
                        <li class="nk-olist-item nk-olist-item-final">
                            <div class="label lead-text">{{ __('Amount transferred to Account') }} <span class="small">*</span></div>
                            <div class="data"><span class="amount">{{ data_get($details, 'tnx_amount') }}</span></div>
                        </li>
                    </ul>
                     <div class="sub-text-sm">{{ __('* Additional fees, network fees or intermediary fees may be deducted from the Amount Transferred by your payment provider.') }}</div>
                </div>
                @if($transaction->is_cancellable)
                <div class="nk-pps-field form-action text-center">
                    <div class="nk-pps-action pt-3">
                        <a href="{{ route('transaction.action', ['status' => 'cancel', 'tnx' => the_hash($transaction->id)]) }}" class="btn btn-outline-danger btn-trans">{{ __('Cancel Order') }}</a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
