<div class="nk-pps-apps">
    <div class="nk-pps-result">
        <em class="icon icon-circle icon-circle-xxl ni ni-cross bg-danger"></em>
        <h4 class="title">{{ __('Opps, payment cancelled!') }}</h4>
        <div class="nk-pps-text md">
            <p class="caption-text">{{ __("You have cancelled your payment via :method. Don't worry, you can make payment any time to deposit your account.", ['method' => $transaction->method_name]) }}</p>
            @if(filled($transaction))
                <p class="sub-text">{{ __('Transaction ID') }} {{ the_tnx($transaction->tnx)  }}</p>
            @endif
        </div>
        <div class="nk-pps-action">
            <ul class="btn-group-vertical align-center gy-3">
                <li><a href="{{ route('deposit') }}" class="btn btn-lg btn-mw btn-primary">{{ __('Deposit More') }}</a></li>
                <li><a href="{{ route('dashboard') }}" class="link link-primary">{{ __('Go back to Dashboard') }}</a></li>
            </ul>
        </div>
        <div class="nk-pps-notes text-center">{!! __('Please do not hesitate to :contact if you have any questions.', [ 'contact' => get_page_link('contact', __('contact us')) ]) !!}</div>
    </div>
</div>
