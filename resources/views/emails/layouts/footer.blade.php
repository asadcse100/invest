<table style="width:100%;max-width:620px;margin:0 auto;">
    <tbody>
    <tr>
        <td style="text-align: center; padding:25px 20px 0;">
            <p style="font-size: 13px;">{!! __(get_email_copyright()) !!}</p>
            @if(!empty(social_links('all')) && count(social_links('all')) > 0) 
            <ul style="margin: 10px -4px 0;padding: 0;">
                @foreach(social_links('all') as $social => $item)
                    @if(isset($item['link']) && $item['link'])
                    <li style="display: inline-block; list-style: none; padding: 4px;">
                        <a style="display: inline-block;" href="{{ $item['link'] }}">
                            {{ ucfirst($social) }}
                            {{-- <img style="width: 28px" src="{{ asset('images/'.strtolower($social).'.png') }}" alt="{{ $item['title'] ?? '' }}"> --}}
                        </a>
                    </li>
                    @endif
                @endforeach
            </ul>
            @endif
        </td>
    </tr>
    </tbody>
</table>
