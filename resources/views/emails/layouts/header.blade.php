@if (!empty(get_mail_branding())) 
    <table style="width:100%;max-width:620px;margin:0 auto;">
        <tbody>
        <tr>
            <td style="text-align: center; padding-bottom:15px">
                <img class="logo-img" style="max-height: 50px; width: auto;" src="{{ get_mail_branding() }}" alt="{{ site_info('name') }}">
            </td>
        </tr>
        </tbody>
    </table>
@endif
