@extends('emails.layouts.master')

@section('body')
    <table style="width:100%;max-width:620px;margin:0 auto;background-color:#ffffff;">
        <tbody>
        @if($greeting)
        <tr>
            <td style="padding: 30px 30px 20px">
                {{ $greeting }}
            </td>
        </tr>
        @endif
        
        <tr>
            <td style="padding: 0 30px">
                {!! auto_p($content) !!}
            </td>
        </tr>

        @if (!empty($others) && !is_array($others))
        <tr>
            <td style="padding: 30px 30px 20px">
                <small>{!! auto_p($others) !!}</small>
            </td>
        </tr>
        @endif
        
        @if(data_get($template, 'params.regards') == "on")
        <tr>
            <td style="padding: 30px 30px 20px">
                {!! auto_p($global_footer) !!}
            </td>
        </tr>
        @endif
        
        </tbody>
    </table>
@endsection
