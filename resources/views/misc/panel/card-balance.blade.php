@php 

$type = isset($type) ? $type : false;
$currency = base_currency();

$card_title = $card_title_tips = $card_sub_title = $card_sub_title_tips = $card_class = '';
$card_cta = isset($attr['cta']) ? $attr['cta'] : false;

$card_id = (isset($attr['id']) && !empty($attr['id'])) ? ' id="'.$attr['id'].'"' : '';
$card_class = (isset($attr['class']) && !empty($attr['class'])) ? ' '.$attr['class'] : '';

if($type=='account') {
    $card_title = __("Available Balance");
    $card_title_tips = __("Main account balance without locked or under progress.");
    $card_sub_title = __("Investment Account");
    $card_sub_title_tips = __("Additional balance in your Investment account.");
}

if($type=='deposit' || $type=='withdraw') {
    $card_title = __("Total :Type", ['type' => __(ucfirst($type)) ]);
    $card_title_tips = __("The total :type amount without under progress.", ['type' => __(ucfirst($type)) ]);
    $card_sub_title = __("This Month");
    $card_sub_title_tips = __("Than last month");
}

@endphp

@if(!empty($type))
<div class="card card-full card-bordered card-wg on-bottom{{ $card_class }}"{{ $card_id }}>
    <div class="card-inner">
        <div class="card-title-group">
            <div class="card-title">
                <h5 class="nk-wgacc-title">{{ $card_title }}</h5>
            </div>
            @if($card_title_tips)
            <div class="card-tools">
                <em class="icon ni ni-info fs-13px text-soft nk-tooltip" title="{{ $card_title_tips }}"></em>
            </div>
            @endif
        </div>
        <div class="card-amount mt-2 mb-1">
            <span class="amount">{{ amount($amount['main'], $currency) }} <span class="currency">{{ $currency }}</span></span>
        </div>
        <div class="card-stats">
            <div class="card-stats-group g-2">
                <div class="card-stats-data">
                    <div class="title fw-bold">
                        {{ $card_sub_title }}
                        @if($type=='account' && $card_sub_title_tips)
                        <em class="icon ni ni-info-fill fs-12px text-soft nk-tooltip" title="{{ $card_sub_title_tips }}"></em>
                        @endif
                    </div>
                    <div class="amount fw-bold">
                    @if($type=='account')
                        {{ amount($amount['sub'], $currency) }} <span class="currency fw-normal">{{ $currency }}</span>
                    @else
                        {{ amount($amount['sub'], $currency) }} <span class="currency fw-normal">{{ $currency }}</span> 
                        @if($card_sub_title_tips)
                            @if(abs($percentage)!==0)
                                <span class="change {{ ($percentage > 0) ? 'up' : 'down'}} tipinfo" title="{{ $card_sub_title_tips }}">
                                    <em class="icon ni ni-arrow-long-{{ ($percentage > 0) ? 'up' : 'down'}}"></em>{{ abs($percentage) }}%
                                </span>
                            @endif
                        @endif
                    @endif
                    </div>
                </div>
            </div>
            <div class="card-stats-ck sm">
                {{-- <canvas class="chart-liner" id="balanceAccountNew"></canvas> --}}
            </div>
        </div>
        @if($type=='account' &&  $card_cta==true)
        <div class="card-action d-md-none">
            <ul class="nk-block-tools g-3 flex-wrap flex-sm-nowrap">
                <li><a href="{{ route('deposit') }}" class="btn btn-primary"><span>{{ __('Deposit') }}</span> <em class="icon ni ni-arrow-long-right"></em></a></li>
                <li><a href="{{ route('user.investment.invest') }}" class="btn btn-secondary"><span>{{ __('Invest & Earn') }}</span> <em class="icon ni ni-arrow-long-right d-none d-sm-inline-block"></em></a></li>
                @if (module_exist('FundTransfer', 'mod') && feature_enable('transfer'))
                    <li class="w-100"><a href="{{ route('user.send-funds.show') }}" class="link link-primary"><span>{{ __('Send Funds') }}</span> <em class="icon ni ni-arrow-long-right"></em></a></li>
                @endif
            </ul>
        </div>
        @endif
    </div>
</div>
@endif