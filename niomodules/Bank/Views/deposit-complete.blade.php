@extends('user.layouts.master')

@section('content')
    <div class="nk-content-body">
        <div class="page-dw wide-xs m-auto">
            <div class="nk-pps-apps">
                <div class="nk-pps-title">
                    <h3 class="title text-center">{{ __("Transfer your Money") }}</h3>
                    <p class="caption-text">{!! __("Your order :orderid has been placed successfully. To complete your deposit, please send the payment of :amount through bank.", ['orderid' => the_tnx(data_get($order, 'tnx')), 'amount' => '<strong class="text-dark">'.money($amount, $currency).'</strong>']) !!}</p>
                </div>
                <div class="nk-pps-data">
                    <p class="sub-text pb-1">{{ __("We've sent an email to you including payment information as below. If you have any question regarding payment information, please contact us.") }}</p>
                    <h5 class="overline-title-alt mt-4">{{ __("Payment Information:") }}</h5>
                    <ul class="nk-olist nk-olist-flat is-aligned is-compact">
                        <li class="nk-olist-item">
                            <div class="label lead-text">{{ __("Payment Amount") }} {{ (data_get($order, 'tnx_fees')) ? '*' : '' }}</div>
                            <div class="data">{{ money($amount, $currency) }}</div>
                        </li>
                        <li class="nk-olist-item">
                            <div class="label lead-text">{{ __("Reference") }}</div>
                            <div class="data">{{ the_tnx(data_get($order, 'tnx')) }}</div>
                        </li>
                    </ul>
                    @if (data_get($order, 'tnx_fees'))
                        <p class="small mt-1">* <em>{{ __('A processing fee of :amount included in total payment amount.', ['amount' => money(data_get($order, 'tnx_fees'), $currency)]) }}</em></p>
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
                <div class="nk-pps-action mt-n2">
                    <ul class="btn-group {{ (sys_settings('deposit_cancel_timeout', 0)===0) ? 'justify-center' : 'justify-between' }} align-center gy-3">
                        @if((sys_settings('deposit_cancel_timeout', 0)!==0) && isset($order->id))
                        <li><a href="{{ route('transaction.action', ['status' => 'cancel', 'tnx' => the_hash($order->id)]) }}" class="link link-danger">{{ __('Cancel Order') }}</a></li>
                        @endif
                        <li><a href="{{ route('dashboard') }}" class="link link-primary"><span>{{ __('Back to Dashboard') }}</span> <em class="icon ni ni-arrow-long-right"></em></a></li>
                    </ul>
                </div>
                <div class="nk-pps-notes">
                    <ul>
                        <li class="alert-note is-plain">
                            <em class="icon ni ni-alert-circle text-primary"></em>
                            <p>{{ __("Your account will credited once we confirm that payment has been received.") }}</p>
                        </li>
                        <li class="alert-note is-plain">
                            <em class="icon ni ni-alert-circle text-primary"></em>
                            <p>{{ __("Ensure that the amount you send is sufficient to cover all such changes by your bank.") }}</p>
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
@endsection
