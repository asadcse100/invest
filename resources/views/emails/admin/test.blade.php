@extends('emails.layouts.master')

@section('body')
<table style="width:100%;max-width:620px;margin:0 auto;background-color:#ffffff;">
    <tbody>
    <tr>
        <td style="padding: 30px 30px 20px">
            <p style="margin-bottom: 10px;"><strong>{!! $content['greeting'] ?? '' !!}</strong></p>
            {!! $content['message'] ?? '' !!}
        </td>
    </tr>
    </tbody>
</table>
@endsection
