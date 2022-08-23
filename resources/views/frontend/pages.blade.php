@extends('frontend.layouts.master')

@section('title', __($pgtitle))
@section('desc', $pgdesc)
@section('keyword', $pgkeyword)

@section('content')
<div class="content-page">
    <div class="nk-block-head nk-block-head-lg text-center wide-xs mx-auto">
        <div class="nk-block-head-content">
            <h2 class="nk-block-title">{{ __($title) }}</h2>
            @if(!blank($subtitle))
            <div class="nk-block-des">
                <p class="lead">{{ __($subtitle) }}</p>
            </div>
            @endif
        </div>
    </div>

    <div class="nk-block card card-bordered card-stretch">
        <div class="card-inner card-inner-lg">
            <article class="entry">
                {!! $content !!}
            </article>
            @if ($showContactForm)
                @include('misc.contact-form')
            @endif
        </div>
    </div>
</div>
@endsection
