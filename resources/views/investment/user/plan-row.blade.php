@php

use App\Enums\InvestmentStatus;

$currency = base_currency();
$is_running = ($plan->status==InvestmentStatus::ACTIVE) ? true : false;
$is_pending = ($plan->status==InvestmentStatus::PENDING) ? true : false;

@endphp

<div class="nk-plan-item">
    <div class="nk-plan-icon{{ ($is_running ? ' is-running' : (($is_pending) ? ' is-pending' : '')) }}">
        <em class="icon ni ni-{{ ($plan->status==InvestmentStatus::ACTIVE) ? 'update' : 'offer' }}"></em>
    </div>
    <div class="nk-plan-info w-max-275px">
        <div class="nk-plan-name">{{ data_get($plan, 'summary_title_alter') }}</div>
        <div class="nk-plan-desc">{{ __('Invested:') }} <span class="amount">{{ money(data_get($plan, 'amount'), $currency) }}</span></div>
    </div>
    <div class="nk-plan-term">
        <div class="nk-plan-start nk-plan-order">
            <span class="nk-plan-label text-soft">{{ __('Start Date') }}</span>
            <span class="nk-plan-value date">{{ show_date(data_get($plan, 'term_start'), true) }}</span>
        </div>
        <div class="nk-plan-end nk-plan-order">
            <span class="nk-plan-label text-soft">{{ __('End Date') }}</span>
            <span class="nk-plan-value date">{{ show_date(data_get($plan, 'term_end'), true) }}</span>
        </div>
    </div>
    <div class="nk-plan-amount">
        <div class="nk-plan-amount-a nk-plan-order">
            <span class="nk-plan-label text-soft">{{ ($plan->status==InvestmentStatus::COMPLETED) ? __('Total Received') : __('Total Return') }}</span>
            <span class="nk-plan-value amount">{{ ($plan->status==InvestmentStatus::COMPLETED) ? money(data_get($plan, 'received'), $currency) : money(data_get($plan, 'total'), $currency) }}</span>
        </div>
        <div class="nk-plan-amount-b nk-plan-order">
            <span class="nk-plan-label text-soft">
                {{ __('Net Profit') }}
            </span>
            <span class="nk-plan-value amount">{{ money(data_get($plan, 'profit'), $currency) }}</span>
        </div>
    </div>
    <div class="nk-plan-more">
        <a class="btn btn-icon btn-lg btn-round btn-trans" href="{{ route('user.investment.details', ['id' => the_hash($plan->id)]) }}"><em class="icon ni ni-forward-ios"></em></a>
    </div>
    @if($plan->status==InvestmentStatus::ACTIVE)
    <div class="nk-plan-progress">
        <div class="progress-bar" data-progress="{{ data_get($plan, 'progress') }}"></div>
    </div>
    @endif
</div>
