@php

use Carbon\Carbon;

$news_class = (isset($attr['class']) && !empty($attr['class'])) ? ' '.$attr['class'] : '';

$news_enable = sys_settings('header_notice_show');
$news_valid = true;

$news_title = sys_settings('header_notice_title');
$news_text = sys_settings('header_notice_text');
$news_link = sys_settings('header_notice_link');

if (!empty(sys_settings('header_notice_date'))) {
    $now = Carbon::now()->tz(time_zone());
    $expiry = Carbon::parse(sys_settings('header_notice_date'), time_zone());
    $news_valid = ($now->lte($expiry->endOfDay())) ? true : false;
}

@endphp

@if($news_enable == 'yes' && $news_valid && ($news_title || $news_text))
<div class="nk-news-list">

    @if($news_title || $news_text)
        @if($news_link) 
            <a class="nk-news-item" target="_blank" href="{{ $news_link }}">
        @else
            <span class="nk-news-item">
        @endif

        <div class="nk-news-icon">
            <em class="icon ni ni-card-view"></em>
        </div>

        <div class="nk-news-text">
            <p>{{ $news_title }}
                @if($news_text)
                <span>{{ $news_text }}</span>
                @endif
            </p>
            @if($news_link)
            <em class="icon ni ni-external"></em>
            @endif
        </div>

        @if($news_link) 
            </a>
        @else
            </span>
        @endif
    @endif

</div>
@endif