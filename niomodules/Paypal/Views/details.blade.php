@php
    $details = data_get($transaction, 'details');
@endphp
<div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
        <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
        <div class="modal-body modal-body-md">
            <div class="nk-pps-apps">
                <div class="nk-pps-title text-center">
                    <h3 class="title">{{ __('Deposit Details') }}</h3>
                    <p class="caption-text">{!! __('You are about to deposit :amount in your account.', ['amount' => '<strong class="text-dark">'.data_get($details, 'amount').'</strong>' ]) !!}</p>
                    <p class="sub-text-sm">{{ __('Please review the information and confirm.') }}</p>
                </div>
                <div class="nk-pps-data">
                    <ul class="nk-olist">
                        <li class="nk-olist-item">
                            <div class="label lead-text">{{ __('Deposit from') }}</div>
                            <div class="data"><span class="method"><em class="icon ni ni-paypal-alt"></em> <span>{{ data_get($paymentMethodDetails, 'name') }}</span></span></div>
                        </li>

                        <li class="nk-olist-item is-grouped">
                            <div class="label lead-text">{{ __('Amount to deposit') }}</div>
                            <div class="data"><span class="amount">{{ data_get($details, 'amount') }}</span></div>
                        </li>
                        <li class="nk-olist-item is-grouped">
                            <div class="label lead-text">{{ __('Fees') }}</div>
                            <div class="data"><span class="amount">{{ data_get($details, 'tnx_fees') }}</span></div>
                        </li>
                        <li class="nk-olist-item is-grouped">
                            <div class="label lead-text">{{ __('Total charge to deposit') }}</div>
                            <div class="data"><span class="amount">{{ data_get($details, 'tnx_total') }}</span></div>
                        </li>
                        <li class="nk-olist-item small is-grouped">
                            <div class="label">{{ __('Exchange rate') }}</div>
                            <div class="data fw-normal text-soft">
                                <span class="amount">{{ __('Exchange rate: 1 :baseCur = :rate', ['baseCur' => data_get($details, 'currency'), 'rate' => data_get($details, 'exchange_rate')]) }}</span>
                            </div>
                        </li>

                        <li class="nk-olist-item small">
                            <div class="label">{{ __('Equivalent to') }}</div>
                            <div class="data fw-normal text-soft"><span class="amount">{{ data_get($details, 'tnx_total') }}</span></div>
                        </li>

                    </ul>
                    <ul class="nk-olist">
                        <li class="nk-olist-item nk-olist-item-final">
                            <div class="label lead-text">{{ __('Amount to credit') }}</div>
                            <div class="data"><span class="amount">{{ data_get($details, 'amount') }}</span></div>
                        </li>
                    </ul>

                    <div class="sub-text-sm">{!! __('* You will be redirect to :gateway website once you confirm.', ['gateway' => '<strong class="text-dark">' . "PayPal" . '</strong>']) !!}</div>
                </div>
                <div class="nk-pps-field form-action text-center">
                    <div class="nk-pps-action">
                        <a href="javascript:void(0)" class="btn btn-lg btn-block btn-primary" id="make-paypal-payment" data-tnx="{{ the_hash($transaction->id) }}" data-url="{{ route('user.gateway.make-payment.paypal') }}">
                            <span>{{ __('Pay Now') }}</span>
                            <span class="spinner-border spinner-border-sm hide" role="status" aria-hidden="true"></span>
                        </a>
                    </div>
                    @if($transaction->is_cancellable)
                    <div class="nk-pps-action pt-3">
                        <a href="{{ route('transaction.action', ['status' => 'cancel', 'tnx' => the_hash($transaction->id)]) }}" class="btn btn-outline-danger btn-trans">{{ __('Cancel Order') }}</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
