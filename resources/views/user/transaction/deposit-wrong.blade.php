<div class="nk-pps-apps">
    <div class="nk-pps-result">
        @if($status == 'failed')
            <em class="icon icon-circle icon-circle-xxl ni ni-alert bg-warning"></em>
        @else
            <em class="icon icon-circle icon-circle-xxl ni ni-cross bg-danger"></em>
        @endif
        <h4 class="title">{{ __('Sorry, unable to proceed!') }}</h4>
        <div class="nk-pps-text sm">
            @if($status == 'failed')
                <p class="caption-text">{{ __('Sorry something wrong in our end while verifying your payment. Please contact us to resolve the payment issue.') }}</p>
            @else
                <p class="caption-text">{{ __('We are temporarily unable to process your deposit request. Please try again later.') }}</p>
            @endif
        </div>
        <div class="nk-pps-action">
            <ul class="btn-group-vertical align-center gy-3">
                <li><a href="{{ route('deposit') }}" class="btn btn-lg btn-mw btn-primary">{{ __('Try Again') }}</a></li>
                <li><a href="{{ route('dashboard') }}" class="link link-primary">{{ __('Go to Dashboard') }}</a></li>
            </ul>
        </div>
        <div class="nk-pps-notes text-center">{!! __('If you continue to having trouble? :Contact or email at :email', ['contact' => get_page_link('contact', __('Contact us')), 'email' => get_mail_link()]) !!}</div>
    </div>
</div>
