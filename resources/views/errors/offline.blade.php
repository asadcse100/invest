<!DOCTYPE html>
<html lang="zxx" class="js">
<head>
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <title>{{ (!empty($title)) ? $title . ' | ' . site_info('name') : __("Website is Offline") }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/apps.css?ver=130') }}">
</head>


<body class="nk-body npc-cryptlite">
<div class="nk-app-root">
    <div class="nk-wrap ">
        <div class="nk-block nk-block-middle wide-xs mx-auto">
            <div class="nk-block-content nk-error-ld text-center">
                <div class="nk-error-icon text-center"><em class="icon ni ni-info"></em></div>
                <div class="wide-xs mx-auto">
                    @if (!empty($heading)) 
                    <h3 class="nk-error-title fw-medium">{{ $heading }}</h3>
                    @endif

                    @if (!empty($message))
                    <p class="nk-error-text">{{ $message }}</p>
                    @else 
                    <p class="nk-error-text">{{ __("We are upgrading our system. Please check after 30 minutes.") }}</p>
                    @endif
                    @if (!empty($support)) 
                        <p class="mt-4 text-soft">{!! $support !!}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>