<div class="nk-pps-apps">
    <div class="nk-pps-steps">
        <span class="step"></span>
        <span class="step"></span>
        <span class="step"></span>
        <span class="step active"></span>
    </div>
    <div class="nk-pps-result">
        <em class="icon icon-circle icon-circle-xxl ni ni-check bg-success"></em>
        <h3 class="title">{{ __('Your funds are on the way!') }}</h3>
        <div class="nk-pps-text md">
            <p class="caption-text">{{ __("We'll send you a confirmation email shortly. Check that email for details on when the funds will reach your account.") }}</p>
            <p class="sub-text">{{ __('Your withdrawal request ID :tnx', ['tnx' => the_tnx(data_get($transaction, 'tnx'))]) }}</p>
        </div>
        <div class="nk-pps-action">
            <ul class="btn-group-vertical align-center gy-3">
                <li><a href="{{ route('withdraw') }}" class="btn btn-lg btn-mw btn-primary">{{ __('Another Withdraw') }}</a></li>
                <li><a href="{{ route('transaction.list') }}" class="link link-primary">{{ __('Check status in Transaction') }}</a></li>
            </ul>
        </div>
    </div>
</div>
