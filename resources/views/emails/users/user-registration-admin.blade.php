@extends('emails.layouts.master')

@section('body')
    <table style="width:100%;max-width:620px;margin:0 auto;background-color:#ffffff;">
        <tbody>
        <tr>
            <td style="padding: 30px 30px 15px 20px;">
                <h2 style="font-size: 18px; color: #6576ff; font-weight: 600; margin: 0;">{{ __('Confirmation of Registration') }}</h2>
            </td>
        </tr>
        <tr>
            <td style="padding: 30px 30px 20px">
                {{ $greeting }}
                {!! auto_p($content) !!}
            </td>
        </tr>
        <tr>
            <td style="padding: 0 30px 20px">
                <p style="margin-bottom: 10px;">{{ __("Email") }}: {{ $user->email }}</p>
                <p style="margin-bottom: 10px;">{{ __("Password") }}: {{ $others['password'] }}</p>

                @if(\Illuminate\Support\Arr::get($others, 'verified') == 'on')
                    <p style="margin-bottom: 10px;">{{ __('Click the link below to active your :site account.', ['site' => sys_settings('site_name')]) }}</p>
                    <p style="margin-bottom: 25px;">{{ __('This link will expire in 30 minutes and can only be used once.') }}</p>
                    <a href="{{ route('auth.email.verify', [ 'token' => data_get($user, 'verify_token.token').md5($user->email) ]) }}" style="background-color:#6576ff;border-radius:4px;color:#ffffff;display:inline-block;font-size:13px;font-weight:600;line-height:44px;text-align:center;text-decoration:none;text-transform: uppercase; padding: 0 30px">{{ __("Verify Email") }}</a>
                @endif
            </td>
        </tr>

        @if(\Illuminate\Support\Arr::get($others, 'verified') == 'on')
        <tr>
            <td style="padding: 0 30px 20px">
                <h4 style="font-size: 15px; color: #000000; font-weight: 600; margin: 0; text-transform: uppercase; margin-bottom: 10px">{{ __('or') }}</h4>
                <p style="margin-bottom: 10px;">{{ __('If the button above does not work, paste this link into your web browser:') }}</p>
                <a href="#" style="color: #6576ff; text-decoration:none;word-break: break-all;">{{ route('auth.email.verify', [ 'token' => data_get($user, 'verify_token.token').md5($user->email) ]) }}</a>
            </td>
        </tr>
        @endif
        <tr>
            <td style="padding: 0 30px 30px">
                <p>{{ __('If you did not make this request, please contact us or ignore this message.') }}</p>
                <p style="margin: 0; font-size: 13px; line-height: 22px; color:#9ea8bb;">{{ __('This is an automatically generated email please do not reply to this email. If you face any issues, please contact us at :site_email', ['site_email' => sys_settings('site_email')]) }}</p>
            </td>
        </tr>
        </tbody>
    </table>

@endsection
