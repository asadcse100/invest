<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
</head>
<body width="100%" style="margin: 0; padding: 0 !important; font-size: 14px; line-height: 24px; color:#1c2b46; font-family: Roboto, Helvetica, Arial, sans-serif; mso-line-height-rule: exactly;">
<table width="100%">
    <tbody>
        <tr>
        <td style="padding:0 10px">
            <p>{{ __('Name: :name', ['name' => $data['name']]) }}</p>
            <p>{{ __('Email: :email', ['email' => $data['email']]) }}</p> 

            @if ($data['phone']) <p>{{ __("Phone: :phone", ['phone' => $data['phone']]) }}</p> @endif

            @if ($data['subject']) <p>{{ __("Subject: :line", ['line' => $data['subject']]) }}</p> @endif

            @if ($data['message']) <p style="margin: 0 0 -10px"><strong>{{ __("Message:") }}</strong></p> {!! $data['message'] !!} @endif

            <p style="margin: 32px 0 0; font-size: 13px; font-style: italic; line-height: 22px; color:#9ea8bb;">
                ---<br>
                {!! __('This email was sent from: :site', ['site' => '<a href="'.url('/').'">'.site_info('name').'</a>']) !!}
            </p>
        </td>
        </tr>
    </tbody>
</table>
</body>
</html>