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
            <td style="padding: 0 30px 20px">
               {{ __("New Password") }}:  <strong>{{ data_get($others, 'random_pass') }}</strong>
            </td>
        </tr>

        <tr>
            <td style="padding: 0 30px 20px">
                <p style="margin-bottom: 25px; font-style: italic;">{{ __('Please note: We have reset your password. We recommended to change the password to set strong password after login into your account.') }}</p>
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
