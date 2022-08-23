@php
$cur_count = count($currencies);
$cls_dd = ($cur_count >= 6) ? '' : (($cur_count > 3) ? ' dropdown-menu-xs' : ' dropdown-menu-xxs');
$cls_ul = ($cur_count >= 6) ? ' li-col3x' : (($cur_count > 3) ? ' li-col2x' : '');

$accounts = $accounts ?? collect([]);
$default = data_get($accounts->first(), 'config.currency', '');
@endphp

<div class="nk-pps-apps">
    <div class="nk-pps-steps">
        <span class="step"></span>
        <span class="step active"></span>
        <span class="step"></span>
        <span class="step"></span>
    </div>
    <div class="nk-pps-title text-center">
        <h3 class="title">{{ __('Withdraw Funds') }}</h3>
        <p class="caption-text">{!! __('via :method', ['method' => '<strong>'.__(data_get($method, 'title')).'</strong>']) !!}</p>
        <p class="sub-text-sm">{{ __(data_get($method, 'desc')) }}</p>
    </div>
    <form class="nk-pps-form" action="{{ route('withdraw.preview.form') }}" method="POST" id="wdm-continue-from">
        <div class="nk-pps-field form-group">
            <div class="form-label-group">
                <label class="form-label">{{ __('Withdraw To') }}</label>
                <a href="javascript:void(0)" data-action="{{route('user.withdraw.account.'.data_get($method, 'slug').'.form', ['quick_added' => true])}}" class="link wd-new-account" data-modal="withdraw-account-modal">
                    {{ __('New :account', ['account' => data_get($method, 'title')]) }}
                </a>
            </div>
            <input type="hidden" value="{{ the_hash(data_get($accounts->first(), 'id')) }}" name="wd_account" id="wdm-account">
            <input type="hidden" value="1" name="wd_amount_by" id="wdm-amount-by">
            <div class="dropdown nk-pps-dropdown">
                <a href="javascript:void(0)" class="dropdown-indicator" data-toggle="dropdown" id="wdm-account-name">
                    <div class="nk-cm-item">
                        <div class="nk-cm-text">
                            @if(blank($accounts))
                                <span class="label">{{ __('Please add :account first', ['account' => data_get($method, 'name')]) }}</span>
                            @elseif (!in_array(data_get($accounts->first(), 'config.currency'), active_currencies()))
                                <span class="label">{{ __('Please select your withdraw account') }}</span>
                            @else
                                <span class="label fw-bold">{{ data_get($accounts->first(), 'name') }}</span>
                                <span class="desc">{{ data_get($accounts->first(), 'account_name') }}</span>
                            @endif
                        </div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-auto dropdown-menu-mxh">
                    <ul class="nk-dlist">
                        @foreach($accounts as $acc)
                            @if (in_array(data_get($acc, 'config.currency'), active_currencies())) 
                                <li class="nk-dlist-item{{ ($loop->first) ? ' selected' : '' }}">
                                    <a href="javascript:void(0)" class="nk-dlist-opt wdm-change" data-change="wdm-account" data-id="{{ the_hash(data_get($acc, 'id')) }}" data-currency="{{ data_get($acc, 'config.currency') }}">
                                        <div class="nk-cm-item">
                                            <div class="nk-cm-text">
                                                <span class="label fw-bold">{{ data_get($acc, 'name', data_get($method, 'name')) }}</span>
                                                <span class="desc">{{ data_get($acc, 'account_name').' '.((data_get($acc, 'last_used')) ? __('(Last used :date)', ['date' => show_date(data_get($acc, 'last_used'))]) : '') }}</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="nk-pps-field-set">
            <div class="nk-pps-field-row row gy-gs">
                <div class="nk-pps-field-col col-12{{ (base_currency()==$default) ? '' : ' col-sm-6' }} wdm-account-fmsa">
                    <div class="nk-pps-field form-group">
                        <div class="form-label-group">
                            <label class="form-label" for="wdm-amount-from">{{ __('Withdraw Amount') }}</label>
                        </div>
                        <div class="form-control-group">
                            <div class="form-text-hint">
                                <span class="overline-title">{{ base_currency() }}</span>
                            </div>
                            <input type="text" class="form-control form-control-lg form-control-number wd-amount" id="wdm-amount-from" name="wd_amount" placeholder="0.00">
                            <input type="hidden" name="wd_currency" id="wdm-base-currency" value="{{ base_currency() }}">
                        </div>
                        <div class="form-note-group">
                            <span class="nk-pps-bal form-note-alt">{{ __('Current Balance:') }} <strong class="text-base amount">{{ money($balance, base_currency()) }}</strong></span>
                        </div>
                    </div>
                </div>

                <div class="nk-pps-field-col col-12 col-sm-6 wdm-account-tora{{ (base_currency()==$default) ? ' hide' : '' }}">
                    <div class="nk-pps-field form-group">
                        <div class="form-label-group">
                            <label class="form-label" for="wdm-amount-to">{!! __('Amount in :currency', ['currency' => '<strong class="wdmcur">'.$default.'</strong>']) !!}</label>
                        </div>
                        <div class="form-control-group">
                            <div class="form-text-hint">
                                <span class="overline-title" id="wdm-account-currency-code">{{ $default }}</span>
                            </div>
                            <input type="text" class="form-control form-control-lg form-control-number wd-amount" id="wdm-amount-to" name="wd_amount_to" placeholder="0.00">
                            <input type="hidden" id="wdm-account-currency" name="wd_currency_to" value="{{ data_get($accounts->first(), 'config.currency', base_currency()) }}">
                        </div>
                        <div class="form-note-group{{ (base_currency()==$default) ? " hide" : "" }}" id="wdm-account-rate">
                            <span class="nk-pps-rate form-note-alt">
                                {!! __(':base = :rate', ['base' => '1 '.base_currency(), 'rate' => '<span class="fxrate">'.money(data_get($rates, $default), $default, ['dp' => 'calc']).'</span>']) !!}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="nk-pps-field form-group">
            <div class="form-label-group">
                <label class="form-label" for="withdraw-desc">{{ __('Description') }} <small class="text-soft fw-normal">({{ __('Optional') }})</small></label>
            </div>
            <div class="form-control-group">
                <input type="text" class="form-control form-control-lg" id="withdraw-desc" name="wd_desc" placeholder="">
            </div>
        </div>
        <div class="nk-pps-field form-action text-center">
            @if (the_data($method, 'fees.service', 'no') == 'yes')
            <div class="form-note pb-3 mt-n1 text-center">{{ __('Processing fee will be included into the amount you withdraw.') }}</div>
            @endif
            <div class="nk-pps-action">
                <a href="#" class="btn btn-lg btn-block btn-primary pps-btn-action" id="wdm-continue">
                    <span>{{ __('Continue to Withdraw') }}</span>
                    <span class="spinner-border spinner-border-sm hide" role="status" aria-hidden="true"></span>
                </a>
            </div>
            <div class="nk-pps-action pt-3">
                <a href="{{route('withdraw')}}" class="btn btn-outline-secondary btn-trans pps-btn-action" data-action="prev">{{ __('Back to previous') }}</a>
            </div>
        </div>
    </form>
    <script type="text/javascript">
        var fxCur = { base: "{{ base_currency() }}", alter: "{{ secondary_currency() }}", rates: @json($rates), data: @json($currencies) }, account = {{ $balance }},
            reqMsg = {required: "{{ __('You must enter your withdraw amount.') }}", balance: "{{ __('The amount exceeds your current balance.') }}", invalid: "{{ __("Sorry, but we're facing some technical issue.") }}"};
    </script>
</div>

