@extends('emails.layouts.master')

@section('body')
    <table style="width:100%;max-width:620px;margin:0 auto;background-color:#ffffff;">
        <tbody>
        <tr>
            <td style="padding: 30px 30px 20px">
                {{ $greeting }}
            </td>
        </tr>

        <tr>
            <td style="padding: 0 30px 20px">
                {!! auto_p($content) !!}
            </td>
        </tr>
        <tr>
            <td style="text-align:center;padding: 0 30px 20px">
                <p style="margin-bottom: 25px;">{{ __('Click On The link blow to reset your password.') }}</p>
                <a href="{{ route('auth.reset.page', [ 'token' => data_get($user, 'verify_token.token').md5($user->email) ]) }}" style="background-color:#6576ff;border-radius:4px;color:#ffffff;display:inline-block;font-size:13px;font-weight:600;line-height:44px;text-align:center;text-decoration:none;text-transform: uppercase; padding: 0 25px">{{ __('Reset Password') }}</a>
            </td>
        </tr>

        @if(data_get($template, 'params.regards') == "on")
            <tr>
                <td style="padding: 20px 30px 30px">
                    {!! auto_p($global_footer) !!}
                </td>
            </tr>
        @endif

        </tbody>
    </table>
@endsection
