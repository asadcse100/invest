@php

$ticker_class = (isset($attr['class']) && !empty($attr['class'])) ? ' '.$attr['class'] : '';
$fxcount = (!empty($fxrates) && is_array($fxrates)) ? count($fxrates) : 0;

@endphp

@if(!empty($fxrates) && is_array($fxrates))
    <div class="nk-marque{{ ($fxcount < 6) ? '-na' : '' }}" data-duration="12000">
        <ul class="rate-list{{ $ticker_class }} rate-plain">
            @foreach($fxrates as $code => $rate)
            <li class="rate-item">
                <div class="rate-title">{{ $fxbase.'/'.$code }} = </div>
                <div class="rate-amount">{{ amount_z($rate, $code, ['dp' => 'calc']) }}</div>
            </li>
            @endforeach
        </ul>
    </div>
@endif
