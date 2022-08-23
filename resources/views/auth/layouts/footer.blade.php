<div class="nk-footer nk-auth-footer-full">
    <div class="container wide-lg">
        @if(Panel::navigation('footer'))
        <div class="row g-3">
            <div class="col-lg-6 order-lg-last">
                {!! Panel::navigation('footer', ['class' => 'justify-content-center justify-content-lg-end']) !!}
            </div>
            <div class="col-lg-6">
                <div class="nk-block-content text-center text-lg-left">
                    <p class="text-soft">{!! __(site_info('copyright')) !!}</p>
                </div>
            </div>
        </div>
        @else 
        <p class="text-soft text-center">{!! __(site_info('copyright')) !!}</p>
        @endif
    </div>
</div>
