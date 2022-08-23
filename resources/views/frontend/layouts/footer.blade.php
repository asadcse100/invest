<footer class="section footer bg-white border-top" id="footer">
    <div class="container wide-lg">
        <div class="row">
            <div class="col-12">
                <div class="footer-content text-center">
                    {!! Panel::socials('footer', ['parent' => true, 'class' => 'icon-list mb-3']) !!}

                    <div class="text-base">{!! (is_admin()) ? '<span class="text-danger font-italic small">'.__("You have logged as a system administrator.").'</span>' : __(site_info('copyright')) !!}</div>

                    {!! Panel::navigation('footer', ['class' => 'justify-content-center py-3']) !!}

                    @if(gss('site_disclaimer'))
                    <p class="text-muted">{{ gss('site_disclaimer') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</footer>