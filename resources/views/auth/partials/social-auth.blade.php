@if(social_auth())
    <div class="text-center pt-4 pb-3"><h6 class="overline-title overline-title-sap"><span>{{ __('OR') }}</span></h6></div>
    <ul class="d-flex flex-column flex-sm-row justify-center gx-3 gy-2">
        @if(social_auth('facebook'))
        <li>
            <a class="btn btn-primary btn-block btn-facebook btn-dim" href="{{ route('auth.social','facebook') }}" >
                <em class="icon ni ni-facebook-f"></em>
                <span>{{ ($type == 'login') ? __("Login with :Social", ['social' => __('facebook')]) : __("Signup with :Social", ['social' => __('facebook')]) }}</span>
            </a>    
        </li>
        @endif
        @if(social_auth('google'))
        <li>
            <a class="btn btn-danger btn-block btn-google btn-dim" href="{{ route('auth.social','google') }}">
                <em class="icon ni ni-google"></em>
                <span>{{ ($type == 'login') ? __("Login with :Social", ['social' => __('google')]) : __("Signup with :Social", ['social' => __('google')]) }}</span>
            </a>
        </li>
        @endif
    </ul>
@endif