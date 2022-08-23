<div class="nk-pps-apps">
    <div class="nk-pps-result">
        <em class="icon icon-circle icon-circle-xxl ni {{ (isset($icon) && $icon) ? $icon : 'ni-alert bg-warning' }}"></em>
        
        @if(isset($title) && $title)
        <h4 class="title">{{ $title }}</h4>
        @endif

        @if(isset($notice) && (data_get($notice, 'caption') || data_get($notice, 'note')))
        <div class="nk-pps-text {{ the_data($notice, 'class', 'md') }}{{ (!$title) ? ' mt-5' : '' }}">
            @if(data_get($notice, 'caption'))
            <p class="caption-text">{{ data_get($notice, 'caption') }}</p>
            @endif
            @if(data_get($notice, 'note'))
                <p class="sub-text-sm">{{ data_get($notice, 'note') }}</p>
            @endif
        </div>
        @endif

        @if((isset($button) && $button) || (isset($link) && $link))
        <div class="nk-pps-action">
            <ul class="btn-group-vertical align-center gy-3">
                @if(isset($button) && is_array($button) && data_get($button, 'text') && data_get($button, 'url'))
                <li><a href="{{ data_get($button, 'url') }}" class="btn btn-lg btn-mw {{ the_data($button, 'class', 'btn-primary') }}">{{ data_get($button, 'text') }}</a></li>
                @endif
                @if(isset($link) && is_array($link) && data_get($link, 'text') && data_get($link, 'url'))
                <li><a href="{{ data_get($link, 'url') }}" class="link {{ the_data($link, 'class', 'link-primary') }}">{{ data_get($link, 'text') }}</a></li>
                @endif
            </ul>
        </div>
        @endif

        @if(isset($help) && $help)
            <div class="nk-pps-notes text-center">
                {!! $help !!}
            </div>
        @endif
    </div>
</div>
