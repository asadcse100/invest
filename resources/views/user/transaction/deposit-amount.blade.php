@php 
$cur_count = count($currencies);
$cls_dd = ($cur_count >= 6) ? '' : (($cur_count > 3) ? ' dropdown-menu-xs' : ' dropdown-menu-xxs');
$cls_ul = ($cur_count >= 6) ? ' li-col3x' : (($cur_count > 3) ? ' li-col2x' : '');
@endphp

<div class="nk-pps-apps">
    <div class="nk-pps-steps">
        <span class="step"></span>
        <span class="step active"></span>
        <span class="step"></span>
        <span class="step"></span>
    </div>
    <div class="nk-pps-title text-center">
        <h3 class="title">{{ __('Deposit Funds') }}</h3>
        <p class="caption-text">{{ __('via') }} <strong>{{ __(data_get($method, 'name')) }}</strong></p>
        <p class="sub-text-sm">{{ __(data_get($method, 'desc')) }}</p>
    </div>
    <form class="nk-pps-form" action="{{ route('deposit.preview.form') }}" id="deposit-amount-form">
        <div class="nk-pps-field-set">
            <div class="nk-pps-field-row row gy-gs">
                <div class="nk-pps-field-col col-12{{ (base_currency()==$default['code']) ? '' : ' col-sm-6'  }} prm-fmsa{{ (gss('deposit_amount_base') == 'no') ? ' nk-pps-only' : ''}}">
                    <div class="nk-pps-field form-group">
                        <div class="form-label-group">
                            <label class="form-label" for="deposit-amount">{{ __('Amount to Deposit') }}</label>
                        </div>
                        <div class="form-control-group">
                            @if($cur_count > 1)
                            <div class="form-dropdown">
                                <div class="dropdown">
                                    <a href="javascript:void(0)" class="dropdown-indicator-caret currency" data-toggle="dropdown" data-offset="0,2" id="prm-currency-name">{{ $default['code'] }}</a>
                                    <div class="dropdown-menu dropdown-menu-right text-center{{ $cls_dd }}">
                                        <ul class="link-list-plain{{ $cls_ul }}" id="currency-list">
                                            @foreach($currencies as $code => $item)
                                                <li><a class="switch-currency" href="javascript:void(0)" data-switch="prm" data-currency="{{ $code }}">{{ $code }}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="form-text-hint form-text-hint-lg">
                                <span class="currency">{{ $default['code'] }}</span>
                            </div>
                            @endif
                            <input type="text" class="form-control form-control-lg form-control-number prm-amount" id="prm-amnt" name="deposit_amount" placeholder="0.00">
                            <input type="hidden" id="prm-currency" name="deposit_currency" value="{{ $default['code'] }}">
                            <input type="hidden" id="prm-amnt-by" name="deposit_amount_by" value="1">
                        </div>

                        <div class="form-note-group">
                            <span class="nk-pps-min form-note-alt">{!! __('Minimum :amount', ['amount' => '<span id="prm-min">'.money( $default['min'], $default['code'], ['dp' => 'calc']).'</span>']) !!}</span>
                            @if (gss('deposit_amount_base') == 'no')
                            <span class="nk-pps-rate prm-rate-u form-note-alt{{ (base_currency()==$default['code']) ? ' hide' : ''  }}">
                                {!! __(':base = :rate', ['base' => '1 '.base_currency(), 'rate' => '<span class="fxrate">'.money($default['rate'], $default['code'], ['dp' => 'calc']).'</span>']) !!}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="nk-pps-field-col col-12 col-sm-6 prm-tora{{ (base_currency()==$default['code']) ? ' hide' : ''  }}">
                    <div class="nk-pps-field form-group">
                        <div class="form-label-group">
                            <label class="form-label" for="credit-amount">{{ __('Amount in :currency', ['currency' => base_currency()]) }}</label>
                        </div>
                        <div class="form-control-group">
                            <div class="form-text-hint form-text-hint-lg">
                                <span class="currency">{{ base_currency() }}</span>
                            </div>
                            <input type="text" class="form-control form-control-lg form-control-number prm-amount" id="prm-credit" name="credit_amount" placeholder="0.00">
                            <input type="hidden" id="prm-credit-currency" name="credit_currency" value="{{ base_currency() }}">
                        </div>
                        @if (gss('deposit_amount_base') != 'no')
                        <div class="form-note-group">
                            <span class="nk-pps-rate prm-rate-u form-note-alt{{ (base_currency()==$default['code']) ? ' hide' : ''  }}">
                                {!! __(':base = :rate', ['base' => '1 '.base_currency(), 'rate' => '<span class="fxrate">'.money($default['rate'], $default['code'], ['dp' => 'calc']).'</span>']) !!}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="nk-pps-field form-action text-center">
            @if (the_data($method, 'fees.service', 'no') == 'yes')
            <div class="form-note pb-3 mt-n1 text-center">{{ __('Processing fee will be apply on your deposit.') }}</div>
            @endif
            <div class="nk-pps-action">
                <a href="javascript:void(0)" class="btn btn-lg btn-block btn-primary" id="pay-next">
                    <span>{{ __('Continue to Deposit') }}</span>
                    <span class="spinner-border spinner-border-sm hide" role="status" aria-hidden="true"></span>
                </a>
            </div>
            <div class="nk-pps-action pt-3">
                <a href="{{ route('deposit') }}" class="btn btn-outline-secondary btn-trans">{{ __('Back to previous') }}</a>
            </div>
        </div>
    </form>
    <script type="text/javascript">
        var fxCur = { base: "{{ base_currency() }}", alter: "{{ secondary_currency() }}", rates: @json($rates), data: @json($currenciesData) };
    </script>
</div>