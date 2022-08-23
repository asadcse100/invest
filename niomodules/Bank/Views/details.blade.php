@php
    $details = data_get($transaction, 'details');
    $bank = data_get($transaction, 'meta.pay_meta');
@endphp
<div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
        <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
        <div class="modal-body modal-body-md">
            <div class="nk-pps-apps">
                <div class="nk-pps-title">
                    <h3 class="title text-center">{{ __("Deposit Details") }}</h3>
                </div>
                <div class="nk-pps-data">
                    <h5 class="overline-title-alt mt-4">{{ __("Deposit Information:") }}</h5>
                    <ul class="nk-olist nk-olist-flat is-aligned is-compact">
                        <li class="nk-olist-item">
                            <div class="label lead-text">{{ __("Amount to deposit") }}</div>
                            <div class="data">{{ data_get($details, 'tnx_amount') }}</div>
                        </li>
                        @if (data_get($details, 'tnx_fees'))
                        <li class="nk-olist-item">
                            <div class="label lead-text">
                                {{ __('Processing fee') }}
                            </div>
                            <div class="data"><span class="amount">{{ data_get($details, 'tnx_fees') }}</span></div>
                        </li>
                        <li class="nk-olist-item">
                            <div class="label lead-text">{{ __("You will send (Total)") }}</div>
                            <div class="data">{{ data_get($details, 'tnx_total') }}</div>
                        </li>
                        @endif

                        @if (data_get($details, 'tnx_currency') != data_get($details, 'base_currency'))
                        <li class="nk-olist-item">
                            <div class="label lead-text">{{ __("Equivalent to") }}</div>
                            <div class="data">
                                {{ data_get($details, 'total') }} <br>
                                <span class="small text-soft">{{ __('Exchange rate: 1 :baseCur = :rate', ['baseCur' => data_get($details, 'currency'), 'rate' => data_get($details, 'exchange_rate')]) }}</span>
                            </div>
                        </li> 
                        @endif

                        <li class="nk-olist-item">
                            <div class="label lead-text">{{ __("Amount to credit") }} <em class="icon ni ni-info small text-soft nk-tooltip" title="{{ __("The amount will be added into your main balance.") }}"></em></div>
                            <div class="data">
                                {{ data_get($details, 'amount') }}
                            </div>
                        </li> 
                    </ul>

                    <h5 class="overline-title-alt mt-4">{{ __("Payment Information:") }}</h5>
                    <ul class="nk-olist nk-olist-flat is-aligned is-compact">
                        <li class="nk-olist-item">
                            <div class="label lead-text">{{ __("Payment Amount") }} {{ (data_get($details, 'tnx_fees')) ? '*' : '' }}</div>
                            <div class="data">{{ data_get($details, 'tnx_total') }}</div>
                        </li>

                        <li class="nk-olist-item">
                            <div class="label lead-text">{{ __("Reference") }}</div>
                            <div class="data">{{ data_get($details, 'order_id') }}</div>
                        </li>
                    </ul>

                    @if (data_get($details, 'tnx_fees'))
                        <p class="small mt-1">* <em>{{ __('A processing fee of :amount included in total payment amount.', ['amount' => data_get($details, 'tnx_fees')]) }}</em></p>
                    @endif

                    @if(data_get($bank, 'account_name') || data_get($bank, 'account_number'))
                        <h5 class="overline-title-alt mt-4">{{ __("Account Information:") }}</h5>
                        <ul class="nk-olist nk-olist-flat is-aligned is-compact">
                            @if(data_get($bank, 'account_name'))
                                <li class="nk-olist-item">
                                    <div class="label lead-text">{{ __("Account Name") }}</div>
                                    <div class="data">{{ data_get($bank, 'account_name') }}</div>
                                </li>
                            @endif
                            @if(data_get($bank, 'account_number'))
                                <li class="nk-olist-item">
                                    <div class="label lead-text">{{ __("Account Number") }}</div>
                                    <div class="data">{{ data_get($bank, 'account_number') }}</div>
                                </li>
                            @endif
                            @if(data_get($bank, 'account_address'))
                                <li class="nk-olist-item">
                                    <div class="label lead-text">{{ __("Account Holder Address") }}</div>
                                    <div class="data">{{ data_get($bank, 'account_address') }}</div>
                                </li>
                            @endif
                        </ul>
                    @endif

                    @if(data_get($bank, 'bank_name'))
                        <h5 class="overline-title-alt mt-4">{{ __("Our Bank Details:") }}</h5>
                        <ul class="nk-olist nk-olist-flat is-plain is-aligned">
                            <li class="nk-olist-item">
                                <div class="label lead-text">{{ __("Bank Name") }}</div>
                                <div class="data">{{ data_get($bank, 'bank_name') }}</div>
                            </li>
                            @if(data_get($bank, 'bank_branch'))
                                <li class="nk-olist-item">
                                    <div class="label lead-text">{{ __("Bank Branch") }}</div>
                                    <div class="data">{{ data_get($bank, 'bank_branch') }}</div>
                                </li>
                            @endif
                            @if(data_get($bank, 'bank_address'))
                                <li class="nk-olist-item">
                                    <div class="label lead-text">{{ __("Bank Address") }}</div>
                                    <div class="data">{{ data_get($bank, 'bank_address') }}</div>
                                </li>
                            @endif
                            @if(data_get($bank, 'sortcode'))
                                <li class="nk-olist-item">
                                    <div class="label lead-text">{{ __("Sort Code") }}</div>
                                    <div class="data">{{ data_get($bank, 'sortcode') }}</div>
                                </li>
                            @endif
                            @if(data_get($bank, 'routing'))
                                <li class="nk-olist-item">
                                    <div class="label lead-text">{{ __("Routing Number") }}</div>
                                    <div class="data">{{ data_get($bank, 'routing') }}</div>
                                </li>
                            @endif
                            @if(data_get($bank, 'iban'))
                                <li class="nk-olist-item">
                                    <div class="label lead-text">{{ __("IBAN") }}</div>
                                    <div class="data">{{ data_get($bank, 'iban') }}</div>
                                </li>
                            @endif
                            @if(data_get($bank, 'swift'))
                                <li class="nk-olist-item">
                                    <div class="label lead-text">{{ __("Swift/BIC") }}</div>
                                    <div class="data">{{ data_get($bank, 'swift') }}</div>
                                </li>
                            @endif
                        </ul>
                    @endif
                </div>
                @if($transaction->is_cancellable)
                <div class="nk-pps-field form-action text-center">
                    <div class="nk-pps-action">
                        <a href="{{ route('transaction.action', ['status' => 'cancel', 'tnx' => the_hash($transaction->id)]) }}" class="btn btn-outline-danger btn-trans">{{ __('Cancel Order') }}</a>
                    </div>
                </div>
                @endif
                <div class="nk-pps-notes">
                    <ul>
                        <li class="alert-note is-plain">
                            <em class="icon ni ni-alert-circle text-primary"></em>
                            <p>{{ __("Your account will credited once we confirm that payment has been received.") }}</p>
                        </li>
                        <li class="alert-note is-plain">
                            <em class="icon ni ni-alert-circle text-primary"></em>
                            <p>{{ __("Ensure that the amount you send is sufficient to cover all such charges by your bank.") }}</p>
                        </li>
                        <li class="alert-note is-plain text-danger">
                            <em class="icon ni ni-alert-circle"></em>
                            <p>{{ __("Please make your payment within 3 days, unless this order will be cancelled.") }}</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
