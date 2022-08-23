<div class="nk-pps-apps">
    <div class="nk-pps-steps">
        <span class="step"></span>
        <span class="step active"></span>
    </div>
    <div class="nk-pps-title text-center">
        <h3 class="title">{{ __('Confirm Your Investment') }}</h3>
        <p class="caption-text">{{ __('Please review your investment plan details and confirm.') }}</p>
    </div>

    <div class="nk-pps-data card card-bordered">
        <ul class="nk-glist text-center">
            <li class="nk-glist-item">
                <div class="sub-text">{{ __('Plan Name') }}</div>
                <div class="lead-text fw-bold">{{ __(data_get($plan, 'name')) }}</div>
            </li>
            <li class="nk-glist-item">
                <div class="sub-text">{{ __('Duration') }}</div>
                <div class="lead-text fw-bold">{{ data_get($plan, 'term_text_alter') }}</div>
            </li>
            <li class="nk-glist-item">
                <div class="sub-text">{{ __(':Rate_type Profit', ['rate_type' => __(data_get($plan, 'calc_period'))]) }}</div>
                <div class="lead-text fw-bold">{{ (data_get($plan, 'rate_type') == 'percent') ? data_get($plan, 'rate').'%' : money(data_get($plan, 'rate'), $currency, ['dp' => 'calc']) }}</div>
            </li>
        </ul>
    </div>
    <div class="nk-pps-data">
        <ul class="nk-olist">
            <li class="nk-olist-item">
                <div class="label lead-text">{{ __('Payment Account') }}</div>
                <div class="data"><span class="method"><em class="icon ni ni-wallet-fill"></em> <span>{{ __('Main Balance') }}</span></span></div>
            </li>
            <li class="nk-olist-item is-grouped">
                <div class="label lead-text">{{ __('Amount to Invest') }}</div>
                <div class="data"><span class="amount">{{ money(data_get($details, 'amount'), $currency, ['dp' => 'calc']) }}</span></div>
            </li>
            <li class="nk-olist-item">
                <div class="label">{{ __('Total Profit Earn') }}</div>
                <div class="data text-soft"><span class="amount">{{ money(data_get($details, 'profit'), $currency, ['dp' => 'calc']) }}</span></div>
            </li>
            <li class="nk-olist-item">
                <div class="label lead-text">{{ __('Total Return') }} {{ (data_get($details, 'scheme.capital', 0)==0) ? __("(inc. cap)") : __("(exc. cap)") }}</div>
                <div class="data"><span class="amount">{{ money(data_get($details, 'total'), $currency, ['dp' => 'calc']) }}</span></div>
            </li>
        </ul>

        <ul class="nk-olist">
            <li class="nk-olist-item nk-olist-item-final">
                <div class="label lead-text">{{ __('Amount to Debit') }}</div>
                <div class="data"><span class="amount">{{ money(data_get($details, 'amount'), $currency, ['dp' => 'calc']) }}</span></div>
            </li>
        </ul>
        <div class="sub-text-sm">
            * {{ __("The amount will be deducted immediately from your account balance once you confirm.") }}
        </div>
    </div>
    <div class="nk-pps-field form-action text-center">
        <div class="nk-pps-action">
            <a href="javascript:void(0)" class="btn btn-lg btn-block btn-primary iv-invest-confirm">
                <span>{{ __('Confirm & Procced') }}</span>
                <span class="spinner-border spinner-border-sm hide" role="status" aria-hidden="true"></span>
            </a>
        </div>
        <div class="nk-pps-action pt-3 mb-n4">
            <a href="{{ route('user.investment.invest') }}" class="btn btn-outline-danger btn-trans">{{ __('Cancel') }}</a>
        </div>
    </div>
</div>
