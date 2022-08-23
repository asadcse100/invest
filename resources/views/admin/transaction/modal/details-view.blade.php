@php
    use \App\Enums\TransactionType as dTType;
    use \App\Enums\TransactionStatus as dTStatus;
    use \App\Enums\TransactionCalcType as dTCType;

    $base_currency = base_currency();

    $amount = $tnx->amount;
    $total = $tnx->total;
    $currency = $tnx->currency;

    $tnx_currency = $tnx->tnx_currency;
    $tnx_amount = $tnx->tnx_amount;
    $tnx_total = $tnx->tnx_amount;
    $exchange = $tnx->exchange;

    $completed_by = data_get($tnx, 'completed_by');
    $confirmed_by = data_get($tnx, 'confirmed_by');

    $pay_to_acc_name = '';

    if ($tnx->tnx_method == 'bank-transfer') {
        $pay_to_acc_name = data_get($tnx, 'meta.pay_meta.account_name');
    }
    if ($tnx->tnx_method == 'crypto-wallet') {
        $pay_to_acc_name = get_currency(data_get($tnx, 'meta.currency'), 'name');
    }
    if ($tnx->tnx_method == 'wd-bank-transfer') {
        $pay_to_acc_name = data_get($tnx, 'meta.pay_meta.payment.acc_name');
    }
    if ($tnx->tnx_method == 'wd-paypal') {
        $pay_to_acc_name = data_get($tnx, 'meta.pay_meta.label');
    }
@endphp

<div class="nk-modal-head mb-3 mb-sm-4">
    <h4 class="nk-modal-title title">{{ __('Transaction') }} <small class="text-primary">#{{ the_tnx(data_get($tnx, 'tnx')) }}</small></h4>
</div>
<div class="nk-block">
    <div class="nk-block-between flex-wrap g-3 pb-1">
        <div class="nk-tnx">
            <div class="nk-tnx-type-badge mr-2">
                {!! tnx_type_icon($transaction, 'tnx-type-icon') !!}
            </div>
            <div class="nk-tnx-text">
                <h5 class="title">{{ money($tnx_amount, $tnx_currency, ['dp' => 'calc']) }}</h5>
                <span class="sub-text mt-n1">{{ show_date(data_get($tnx, 'created_at'), true) }}</span>
            </div>
        </div>
        <ul class="align-center flex-wrap gx-3">
            <li>
                <span class="badge badge-sm{{ css_state_tnx($tnx->status, 'badge') }}">
                    {{ ($tnx->type == dTType::INVESTMENT && in_array($tnx->status, [dTStatus::PENDING])) ? __("Locked") : __(ucfirst($tnx->status)) }}
                </span>
            </li>
        </ul>
    </div>
    <div class="divider md stretched"></div>
    <div class="row gy-1">
        <div class="col-md-6">
            <h6 class="overline-title">{{ __('In Account') }}</h6>
            <div class="row gy-1">
                @if(!in_array($tnx->type, [dTType::TRANSFER]))
                <div class="col-12">
                    <span class="sub-text">{{ __('Amount') }}</span>
                    <span class="caption-text">{{ money($amount, $base_currency, ['dp' => 'calc']) }}</span>
                </div>
                @endif
                @if(!in_array($tnx->type, [dTType::BONUS, dTType::TRANSFER, dTType::REFERRAL]))
                <div class="col-12">
                    <span class="sub-text">{{ __('Fees') }}</span>
                    <span class="caption-text">
                        {{ money(data_get($tnx, 'fees', '0'), $base_currency) }}
                    </span>
                </div>
                @endif
                <div class="col-12">
                    @if($tnx->type == dTType::TRANSFER)
                    <span class="sub-text">{{ __('Amount to :Calc', ['calc' => __(ucfirst($tnx->calc))]) }}</span>
                    @else
                    <span class="sub-text">{{ __('Total :Type', ['type' => __(ucfirst(data_get($tnx, 'type')))]) }}</span>
                    @endif
                    <span class="caption-text fw-bold">{{ money($total, $base_currency, ['dp' => 'calc']) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <h6 class="overline-title">{{ __('In Transaction') }}</h6>
            <div class="row gy-1">
                @if(!in_array($tnx->type, [dTType::TRANSFER]))
                <div class="col-12">
                    <span class="sub-text">{{ __('Amount') }}</span>
                    <span class="caption-text">{{ money($tnx_amount, $tnx_currency, ['dp' => 'calc']) }}</span>
                </div>
                @endif

                @if(!in_array($tnx->type, [dTType::BONUS, dTType::TRANSFER, dTType::REFERRAL]))
                <div class="col-12">
                    <span class="sub-text">{{ __('Fees') }}</span>
                    <span class="caption-text">
                        {{ money(data_get($tnx, 'tnx_fees', '0'), $tnx_currency, ['dp' => 'calc']) }}
                        @if (data_get($tnx, 'tnx_fees'))
                        <span class="small text-soft">
                            ({{ __(':flat + :percent', ['flat' => amount(data_get($tnx, 'meta.fees.fee.flat'), $tnx_currency, ['dp' => 'calc']), 'percent' => amount(data_get($tnx, 'meta.fees.fee.percent'), $tnx_currency, ['dp' => 'calc'])]) }})
                            <em class="icon ni ni-info fs-13px nk-tooltip" title="{{ __(':flat + :percent fee applied', ['flat' => money(data_get($tnx, 'meta.fees.calc.fx'), $tnx_currency, ['dp' => 'calc']), 'percent' => amount(data_get($tnx, 'meta.fees.calc.pc'), $tnx_currency, ['dp' => 'calc']). ' %' ]) }}"></em>
                        </span>
                        @endif
                    </span>
                </div>
                @endif
                <div class="col-12">
                    @if ($tnx->type == dTType::WITHDRAW)
                    <span class="sub-text">{{ __('Total Withdraw') }}</span>
                    @elseif($tnx->type == dTType::DEPOSIT)
                    <span class="sub-text">{{ __('Total Payment') }}</span>
                    @elseif($tnx->type == dTType::TRANSFER)
                    <span class="sub-text">{{ __('Transfer Amount') }}</span>
                    @else
                    <span class="sub-text">{{ __('Total Amount') }}</span>
                    @endif
                    <span class="caption-text fw-bold">{{ money(data_get($tnx, 'tnx_total', '-'), $tnx_currency, ['dp' => 'calc']) }}</span>
                    @if ($tnx->type == dTType::REFERRAL)
                    <span class="small text-soft nk-tooltip" title="{{ __('Deposit') . ': ' . money(data_get($tnx, 'meta.referral.tnx_amount'), base_currency(), ['dp' => 'calc']) }}"><em class="icon ni ni-info-fill"></em></span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <span class="sub-text">{{ __('User Account') }}</span>
            <span class="caption-text">
                {{ the_uid($tnx->customer->id) }}
                <span class="small text-soft nk-tooltip" title="{{ $tnx->customer->name }}"><em class="icon ni ni-info-fill"></em></span>
            </span>
        </div>
        <div class="col-md-6">
            <span class="sub-text">{{ __('User Email') }}</span>
            <span class="caption-text">
                {{ str_protect($tnx->customer->email) }}
            </span>
        </div>

        @if($tnx_currency!=$currency)
        <div class="col-md-6">
            <span class="sub-text">{{ __('Exchange Rate') }}</span>
            <span class="caption-text">{{ __('1 :from = :rate', ['rate' => money($exchange, $tnx_currency, ['dp' => 'calc']), 'from' => $base_currency]) }}</span>
        </div>
        @endif

        @if($base_currency!=$currency)
        <div class="col-12">
            <div class="note-text mt-2">
                <p class="text-danger mb-1">{{ __("Attention: Current base currency (:system) does not match with this transaction currency (:tnx).", ['system' => $base_currency, 'tnx' => $currency]) }}</p>
                <p>{{ __('Note: System base currency was changed after transaction made. ') }}</p>
            </div>
        </div>
        @endif
    </div>

    <div class="divider md stretched"></div>
    <h6 class="title">{{ __('Order Details') }}</h6>
    <div class="row gy-1">
        <div class="col-md-6">
            <span class="sub-text">{{ __('Order Date') }}</span>
            <span class="caption-text text-break">{{ show_date(data_get($tnx, 'created_at')) }}</span>
        </div>
        <div class="col-md-6">
            <span class="sub-text">{{ __('Placed By') }}</span>
            <span class="caption-text">
                {{ the_uid($tnx->transaction_by->id) }}
                <span class="small text-soft nk-tooltip" title="{{ $tnx->transaction_by->name . ' ('.str_protect($tnx->transaction_by->email).')' }}"><em class="icon ni ni-info-fill"></em></span>
            </span>
        </div>
        @if(data_get($tnx, 'confirmed_at'))
        <div class="col-md-6">
            <span class="sub-text">{{ __('Confirmed At') }}</span>
            <span class="caption-text text-break">{{ show_date(data_get($tnx, 'confirmed_at'), true) }}</span>
        </div>
        <div class="col-md-6">
            <span class="sub-text">{{ __('Confirmed By') }}</span>
            <span class="caption-text">{!! (isset($confirmed_by['name']) ? $confirmed_by['name'] : '<em class="text-soft small">'. __('Unknown') .'</em>') !!}</span>
        </div>
        @endif
        @if(data_get($tnx, 'completed_at'))
        <div class="col-md-6">
            <span class="sub-text">{{ __('Completed At') }}</span>
            <span class="caption-text text-break">{{ show_date(data_get($tnx, 'completed_at'), true) }}</span>
        </div>

        <div class="col-md-6">
            <span class="sub-text">{{ __('Completed By') }}</span>
            <span class="caption-text">{!! (isset($completed_by['name']) ? $completed_by['name'] : '<em class="text-soft small">'. __('System') .'</em>') !!}</span>
        </div>
        @endif
    </div>

    <div class="divider md stretched"></div>
    <h6 class="title">{{ __('Additional Details') }}</h6>
    <div class="row gy-2">
        <div class="col-lg-6">
            <span class="sub-text">{{ __('Transaction Type') }}</span>
            <span class="caption-text">{{ ucfirst(data_get($tnx, 'type')) }}</span>
        </div>
        <div class="col-lg-6">
            <span class="sub-text">{{ __('Payment Gateway') }}</span>
            <span class="caption-text align-center">{{ data_get($tnx, 'method_name') }}
                @if(data_get($tnx, 'is_online') == 1)
                    <span class="badge badge-primary ml-2 text-white">{{ __('Online Gateway') }}</span>
                @endif
            </span>
        </div>

        @if (data_get($tnx, 'type') == 'referral' && !empty(get_user(data_get($tnx, 'meta.referral.user'))))
        <div class="col-lg-6">
            <span class="sub-text">{{ __('Related to Account') }}</span>
            <span class="caption-text">
                {{ str_protect(get_user(data_get($tnx, 'meta.referral.user'))->username) }}
                <span class="small text-soft nk-tooltip" title="{{ the_uid(get_user(data_get($tnx, 'meta.referral.user'))->id) }}"><em class="icon ni ni-info-fill"></em></span>
            </span>
        </div>
        @endif
        
        @if (data_get($tnx, 'type') == 'referral')
        <div class="col-lg-6">
            <span class="sub-text">{{ __('Commission') }}</span>
            @if (data_get($tnx, 'meta.referral.calc') == 'fixed')
            <span class="caption-text">{{ __("Flat amount on :type", ['type' => data_get($tnx, 'meta.referral.action')]) }}</span>
            @else
            <span class="caption-text">{{ __(":amount on :type", ['type' => data_get($tnx, 'meta.referral.action'), 'amount' => data_get($tnx, 'meta.referral.bonus')."%"]) }}</span>
            @endif
            @if (!in_array(data_get($tnx, 'meta.referral.level'), ['lv1', 'lv0']))
            &nbsp;/ {{ str_replace('lv', __('Level#'), data_get($tnx, 'meta.referral.level')) }}
            @endif
        </div>
        @endif

        @if(data_get($tnx, 'pay_from'))
        <div class="col-lg-6">
            <span class="sub-text">{{ __('Payment From') }}</span>
            <span class="caption-text text-break">
                @if(w2n(data_get($tnx, 'pay_from')))
                <span class="small">{{ w2n(data_get($tnx, 'pay_from')) }}</span>
                @elseif (data_get($tnx, 'pay_from'))
                <span class="small">{{ data_get($tnx, 'pay_from', '~') }} {!! !empty(data_get($tnx, 'meta.admin_added')) ? '<em class="ni ni-info nk-tooltip small" title="'.__("Added by Admin").'"></em>' : "" !!}</span>
                @endif
            </span>
        </div>
        @endif

        @if(data_get($tnx, 'reference'))
        <div class="col-lg-6">
            <span class="sub-text">{{ __('Reference / Hash') }}</span>
            <span class="caption-text text-break">{{ data_get($tnx, 'reference', '~') }}</span>
        </div>
        @endif

        @if(data_get($tnx, 'pay_to'))
        <div class="col-lg-6">
            <span class="sub-text">{{ __('Payment To') }}
                @if($pay_to_acc_name)
                <small>({{ $pay_to_acc_name }})</small>
                @endif
                @php
                    $network = data_get($tnx, 'meta.pay_meta.network') ? data_get($tnx, 'meta.pay_meta.network') : '';
                    $currency_name = get_currency($tnx_currency, 'name');
                    $wallet_name = (!empty($network) && $network != 'default') ? $currency_name.' ('.__(short_to_full($network)).')' : '';
                @endphp
                {!! ($wallet_name) ? '<em class="ni ni-info text-soft nk-tooltip small" title="'.$wallet_name.'"></em>' : '' !!}
            </span>
            <span class="caption-text text-break">
                <span class="small">{{ w2n(data_get($tnx, 'pay_to', '~')) }}</span>
            </span>
            @if (data_get($tnx, 'tnx_method') === 'wd-bank-transfer')
            <a href="#see-details" class="link link-btn link-primary popup-open ml-2">{{ __('Show Details') }}</a>
            @endif
        </div>
        @endif

        @if(data_get($tnx->ledger,'balance'))
        <div class="col-lg-6">
            <span class="sub-text">{{ __('Updated Balance') }}</span>
            <span class="caption-text">{{ money(data_get($tnx->ledger, 'balance'), base_currency(), ['dp' => 'calc']) }}</span>
        </div>
        @endif

        @if(data_get($tnx, 'description'))
        <div class="col-lg-12">
            <span class="sub-text">{{ __('Transaction Details') }}</span>
            <span class="caption-text">{{ __(data_get($tnx, 'description')) }}</span>
            @if (data_get($tnx, 'meta.transfer') == 'auto')
                <span class="small text-soft nk-tooltip" title="{{ __('Automatic') }}"><em class="icon ni ni-info-fill"></em></span>
            @endif
        </div>
        @endif

        @if(data_get($tnx, 'meta.unote'))
        <div class="col-lg-6">
            <span class="sub-text">{{ __('Description by User') }}</span>
            <span class="caption-text">{{ data_get($tnx, 'meta.unote') }}</span>
        </div>
        @endif

        @if(data_get($tnx, 'note'))
        <div class="col-lg-6">
            <span class="sub-text">{{ __('Admin Note for User') }}</span>
            <span class="caption-text">{{ data_get($tnx, 'note') }}</span>
        </div>
        @endif

        @if(data_get($tnx, 'remarks'))
        <div class="col-lg-6">
            <span class="sub-text text-danger">{{ __('Remarks by Admin') }}</span>
            <span class="caption-text">{{ data_get($tnx, 'remarks') }}</span>
        </div>
        @endif

        @if (data_get($tnx, 'tnx_method') === 'wd-bank-transfer')
        <div id="see-details" class="popup">
            <div class="popup-content px-3 py-2 px-md-4 py-md-3 mx-auto w-85">
                <div class="card-title-group">
                    <div class="card-title">
                        <h5>{{ __('Bank Account Details') }}</h5>
                    </div>
                    <div class="card-tools">
                        <a href="#" class="link link-btn link-danger popup-close">{{ __('Close') }}</a>
                    </div>
                </div>
                <table class="table table-plain table-borderless table-xs mb-0">
                    @if (data_get($tnx, 'meta.pay_meta.payment.acc_type'))
                    <tr>
                        <td><span class="sub-text">{{ __('Account Type') }}</span></td>
                        <td><span class="lead-text">{{ ucfirst(data_get($tnx, 'meta.pay_meta.payment.acc_type')) }}</span></td>
                    </tr>
                    @endif
                    @if (data_get($tnx, 'metapay_meta.payment.acc_name'))
                    <tr>
                        <td><span class="sub-text">{{ __('Account Name') }}</span></td>
                        <td><span class="lead-text">{{ data_get($tnx, 'metapay_meta.payment.acc_name') }}</span></td>
                    </tr>
                    @endif
                    @if (data_get($tnx, 'meta.pay_meta.payment.acc_no'))
                    <tr>
                        <td><span class="sub-text">{{ __('Account Number') }}</span></td>
                        <td><span class="lead-text">{{ data_get($tnx, 'meta.pay_meta.payment.acc_no') }}</span></td>
                    </tr>
                    @endif
                    @if (data_get($tnx, 'meta.pay_meta.payment.currency'))
                    <tr>
                        <td><span class="sub-text">{{ __('Account Currency') }}</span></td>
                        <td><span class="lead-text">{{ data_get($tnx, 'meta.pay_meta.payment.currency') }}</span></td>
                    </tr>
                    @endif
                    @if (data_get($tnx, 'meta.pay_meta.payment.bank_name'))
                    <tr>
                        <td><span class="sub-text">{{ __('Bank Name') }}</span></td>
                        <td><span class="lead-text">{{ data_get($tnx, 'meta.pay_meta.payment.bank_name') }}</span></td>
                    </tr>
                    @endif
                    @if (data_get($tnx, 'meta.pay_meta.payment.bank_branch'))
                    <tr>
                        <td><span class="sub-text">{{ __('Branch') }}</span></td>
                        <td><span class="lead-text">{{ data_get($tnx, 'meta.pay_meta.payment.bank_branch') }}</span></td>
                    </tr>
                    @endif
                    @if (data_get($tnx, 'meta.pay_meta.payment.bank_address'))
                    <tr>
                        <td><span class="sub-text">{{ __('Bank Address') }}</span></td>
                        <td><span class="lead-text">{{ data_get($tnx, 'meta.pay_meta.payment.bank_address') }}</span></td>
                    </tr>
                    @endif
                    @if (data_get($tnx, 'meta.pay_meta.payment.country'))
                    <tr>
                        <td><span class="sub-text">{{ __('Country') }}</span></td>
                        <td><span class="lead-text">{{ data_get($tnx, 'meta.pay_meta.payment.country') }}</span></td>
                    </tr>
                    @endif
                    @if (data_get($tnx, 'meta.pay_meta.payment.sortcode'))
                    <tr>
                        <td><span class="sub-text">{{ __('Sort Code') }}</span></td>
                        <td><span class="lead-text">{{ data_get($tnx, 'meta.pay_meta.payment.sortcode') }}</span></td>
                    </tr>
                    @endif
                    @if (data_get($tnx, 'meta.pay_meta.payment.routing'))
                    <tr>
                        <td><span class="sub-text">{{ __('Routing Number') }}</span></td>
                        <td><span class="lead-text">{{ data_get($tnx, 'meta.pay_meta.payment.routing') }}</span></td>
                    </tr>
                    @endif
                    @if (data_get($tnx, 'meta.pay_meta.payment.swift'))
                    <tr>
                        <td><span class="sub-text">{{ __('Swift Code / BIC') }}</span></td>
                        <td><span class="lead-text">{{ data_get($tnx, 'meta.pay_meta.payment.swift') }}</span></td>
                    </tr>
                    @endif
                    @if (data_get($tnx, 'meta.pay_meta.payment.iban'))
                    <tr>
                        <td><span class="sub-text">{{ __('IBAN Number') }}</span></td>
                        <td><span class="lead-text">{{ data_get($tnx, 'meta.pay_meta.payment.iban') }}</span></td>
                    </tr>
                    @endif
                </table>
            </div>
            <div class="popup-overlay"></div>
        </div>
        @endif
    </div>
</div>
<script type="text/javascript">
    !(function (App) { App.Popup(); })(NioApp);
</script>
