@extends('user.layouts.master')

@section('title', __(':Name | Investment Plan', ['name' => __(data_get($invest, 'summary_title_alter'))]))

@php
use App\Enums\InvestmentStatus;
use App\Enums\InterestRateType;
use App\Enums\SchemePayout;

$currency = base_currency();
$lastNote = data_get($invest->ledgers->last(), 'note');

@endphp

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head">
            <div class="nk-block-head-sub"><a href="{{ route('user.investment.dashboard') }}" class="text-soft back-to"><em class="icon ni ni-arrow-left"> </em><span>{{ __("Investment") }}</span></a></div>
            <div class="nk-block-between-md g-4">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title fw-normal">{{ data_get($invest, 'summary_title_alter') }}</h3>
                    <div class="nk-block-des">
                        {{ the_inv($invest->ivx) }} <span class="badge{{ the_state($invest->status, ['prefix' => 'badge']) }} ml-1">{{ ucfirst($invest->status) }}</span>
                        @if (data_get($invest, 'cancelled_by') == auth()->id())
                            <span class="text-danger ml-1">{{ __('You have cancelled the investment plan.') }}</span>
                        @endif
                    </div>
                </div>
                @if($invest->status==InvestmentStatus::PENDING || $invest->status==InvestmentStatus::ACTIVE || $invest->status==InvestmentStatus::INACTIVE)
                <div class="nk-block-head-content">
                    <ul class="nk-block-tools gx-3">
                        @if(data_get($invest, 'user_can_cancel') == true)
                        <li class="order-md-last">
                            <button type="button" class="btn btn-danger iv-invest-cancel" data-action="cancelled" data-confirm="yes">
                                <em class="icon ni ni-cross"></em>
                                <span>{{ __('Cancel this plan') }}</span>
                            </button>
                        </li>
                        @endif
                        <li><a href="{{ route('user.investment.details', ['id' => the_hash($invest->id)]) }}" class="btn btn-icon btn-white btn-light"><em class="icon ni ni-reload"></em></a></li>
                    </ul>
                </div>
                @endif
            </div>
        </div>
        <div class="nk-block">
            @if (filled($lastNote) && data_get($invest, 'status') == InvestmentStatus::CANCELLED)
                <div class="alert alert-danger alert-icon">
                    <em class="icon ni ni-info-fill"></em> {{ $lastNote }}
                </div>
            @endif
            @if($invest->status === InvestmentStatus::ACTIVE && $invest->payout_type === SchemePayout::AFTER_MATURED)
                <div class="alert alert-primary alert-thick alert-plain">
                    <div class="alert-cta flex-wrap flex-md-nowrap g-2">
                        <div class="alert-text has-icon">
                            <em class="icon ni ni-info-fill text-primary"></em>
                            @if (data_get($invest, 'term_count') == data_get($invest, 'term_total'))
                                <p>{{ __('This investment plan has been matured, you will be get paid very soon.') }}</p>
                            @else
                                <p>{{ __('You will be paid once this investment plan is matured.') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
            <div class="card card-bordered">
                <div class="card-inner">
                    <div class="row gy-gs">
                        <div class="col-md-6">
                            <div class="nk-wgacc">
                                <div class="nk-wgacc-group flex-lg-nowrap gx-4">
                                    <div class="nk-wgacc-sub">
                                        <div class="nk-wgacc-amount">
                                            <div class="number">{{ amount_z($invest->amount, $currency) }} <span class="fw-normal text-base">{{ $currency }}</span></div>
                                        </div>
                                        <div class="nk-wgacc-subtitle">{{ __('Invested') }}</div>
                                    </div>
                                    <div class="nk-wgacc-sub">
                                        <span class="nk-wgacc-sign text-soft"><em class="icon ni ni-plus"></em></span>
                                        <div class="nk-wgacc-amount">
                                            <div class="number">{{ amount_z($invest->profit, $currency) }}</div>
                                        </div>
                                        <div class="nk-wgacc-subtitle">{{ __('Profit') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 offset-lg-2">
                            <div class="nk-wgacc pl-md-3">
                                <div class="nk-wgacc-group flex-lg-nowrap gx-4">
                                    <div class="nk-wgacc-sub">
                                        <div class="nk-wgacc-amount">
                                            <div class="number">
                                                {{ amount_z($invest->received, $currency) }} <span class="fw-normal text-base">{{ $currency }}</span>
                                            </div>
                                        </div>
                                        <div class="nk-wgacc-subtitle">
                                            {{ __('Total Returned') }} {{ (data_get($invest, 'scheme.capital', 0)==0) ? __("(inc. cap)") : (($invest->status != InvestmentStatus::COMPLETED) ? __("(exc. cap)") : '') }} 
                                            @if($invest->profit_locked > 0)
                                            <em class="icon ni ni-info nk-tooltip text-soft" title="{{ __('The amount (:profit) may locked or pending to adjust into your investment account.', ['profit' => money($invest->profit_locked, $currency)]) }}"></em>
                                            @endif                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="nk-plan-details">
                    <ul class="nk-wgacc-list">
                        <li>
                            <div class="sub-text">{{ __('Term basis') }}</div>
                            <div class="lead-text">{{ __(":Calc", ['calc' => __(data_get($invest, 'term_calc'))]) }}</div>
                        </li>
                        <li>
                            <div class="sub-text">{{ __('Term duration') }}</div>
                            <div class="lead-text">{{ data_get($invest, 'term_text_alter') }}</div>
                        </li>
                        <li>
                            <div class="sub-text">{{ __('Term start at') }}</div>
                            <div class="lead-text">{{ show_date(data_get($invest, 'term_start'), true) ?? __('N/A') }}</div>
                        </li>
                        <li>
                            <div class="sub-text">{{ __('Term end at') }}</div>
                            <div class="lead-text">{{ show_date(data_get($invest, 'term_end'), true) ?? __('N/A') }}</div>
                        </li>
                    </ul>
                    <ul class="nk-wgacc-list">
                        <li>
                            <div class="sub-text">{{ __('Interest (:frequency)', ['frequency' => __(data_get($invest, 'scheme.calc_period'))]) }}</div>
                            <div class="lead-text">{{ data_get($invest, 'rate_text') }}</div>
                        </li>
                        <li>
                            <div class="sub-text">{{ __('Total net profit') }}</div>
                            <div class="lead-text"><span class="currency">{{ $currency }}</span> {{ amount_z(data_get($invest, 'profit'), $currency) }}</div>
                        </li>
                        <li>
                            <div class="sub-text">{{ __(':Calc profit (:capital)', ['calc' => __(data_get($invest, 'scheme.calc_period')), 'capital' => (data_get($invest, 'scheme.capital', 0)==0) ? __("inc. cap") : __("exc. cap") ]) }}</div>
                            <div class="lead-text"><span class="currency">{{ $currency }}</span> {{ amount_z(data_get($invest, 'calc_profit'), $currency) }}</div>
                        </li>
                        <li>
                            <div class="sub-text">{{ __('Adjust profit') }}</div>
                            <div class="lead-text">
                                {{ __(":count / :total times", ['count' => data_get($invest, 'term_count'), 'total' => data_get($invest, 'term_total')]) }}
                            </div>
                        </li>
                    </ul>
                    <ul class="nk-wgacc-list">
                        <li>
                            <div class="sub-text">{{ __('Ordered date') }}</div>
                            <div class="lead-text">{{ show_date(data_get($invest, 'order_at'), true) }}</div>
                        </li>
                        <li>
                            <div class="sub-text">{{ __('Payment source') }}</div>
                            <div class="lead-text">{{ (data_get($invest, 'payment_source')) ? w2n(data_get($invest, 'payment_source')) : __("N/A") }}</div>
                        </li>
                        <li>
                            <div class="sub-text">{{ __('Payment reference') }}</div>
                            <div class="lead-text">{{ data_get($invest, 'reference', __("N/A")) }}</div>
                        </li>
                        <li>
                            <div class="sub-text">{{ __('Paid amount') }}</div>
                            <div class="lead-text">
                                @if($invest->status!=InvestmentStatus::PENDING)
                                <span class="currency">{{ $currency  }}</span> {{ amount_z(data_get($invest, 'paid_amount'), $currency) }}
                                @else
                                {{ __("N/A") }}
                                @endif
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <h5 class="nk-block-title">{{ __('Graph View') }}</h5>
            </div>
            <div class="row g-gs">
                <div class="col-lg-5">
                    <div class="card card-bordered h-100">
                        <div class="card-inner justify-center text-center h-100">
                            <div class="nk-wgpg">
                                <div class="nk-wgpg-head">
                                    <h5 class="nk-wgpg-title">{{ __('Overview') }}</h5>
                                </div>
                                <div class="nk-wgpg-graph">
                                    <input type="text" class="knob-half" value="{{ data_get($invest, 'progress') }}" data-fgColor="#6576ff" data-bgColor="#d9e5f7" data-thickness=".06" data-width="300" data-height="155" data-displayInput="false">
                                    <div class="nk-wgpg-graph-result">
                                        <div class="text-lead">{{ data_get($invest, 'progress') }}%</div>
                                        <div class="text-sub">{{ data_get($invest, 'rate_text') }} / {{ strtolower(data_get($invest, 'period_text')) }}</div>
                                    </div>
                                    <div class="nk-wgpg-graph-minmax"><span>{{ money(0.0, $currency) }}</span><span>{{ money(data_get($invest, 'total'), $currency) }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg col-sm-6">
                    <div class="card card-bordered h-100">
                        <div class="card-inner justify-center text-center h-100">
                            <div class="nk-wgpg">
                                <div class="nk-wgpg-head">
                                    <h5 class="nk-wgpg-title">{{ __('Net Profit') }}</h5>
                                    <div class="nk-wgpg-subtitle">{!! __('Earn so far :amount', ['amount' => '<strong>' . money(data_get($invest, 'received'), $currency) . '</strong>']) !!}</div>
                                </div>
                                <div class="nk-wgpg-graph sm">
                                    <input type="text" class="knob-half" value="{{ data_get($invest, 'progress') }}" data-fgColor="#33d895" data-bgColor="#d9e5f7" data-thickness=".07" data-width="240" data-height="125" data-displayInput="false">
                                    <div class="nk-wgpg-graph-result">
                                        <div class="text-lead sm">{{ str_replace($currency, '', data_get($invest, 'rate_text')) }}</div>
                                        <div class="text-sub">{{ __(':calc profit', ['calc' => __(data_get($invest, 'scheme.calc_period'))]) }}</div>
                                    </div>
                                    <div class="nk-wgpg-graph-minmax"><span>{{ money(0.0, $currency) }}</span><span>{{ money(data_get($invest, 'profit'), $currency) }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg col-sm-6">
                    <div class="card card-bordered h-100">
                        <div class="card-inner justify-center text-center h-100">
                            <div class="nk-wgpg">
                                <div class="nk-wgpg-head">
                                    <h5 class="nk-wgpg-title">{{ __('Remain') }}</h5>
                                    <div class="nk-wgpg-subtitle">{!! __('Adjusted so far :count', ['count' => '<strong>' . data_get($invest, 'term_count') . ' '.__('times').'</strong>']) !!}</div>
                                </div>
                                <div class="nk-wgpg-graph sm">
                                    <input type="text" class="knob-half" value="{{ data_get($invest, 'progress') }}" data-fgColor="#816bff" data-bgColor="#d9e5f7" data-thickness=".07" data-width="240" data-height="125" data-displayInput="false">
                                    <div class="nk-wgpg-graph-result">
                                        <div class="text-lead sm">{{ data_get($invest, 'remaining_term') }}</div>
                                        <div class="text-sub">{{ __('remain to adjust') }}</div>
                                    </div>
                                    <div class="nk-wgpg-graph-minmax"><span>{{ __('0 Time') }}</span><span>{{ __(':count Times', ['count' => data_get($invest, 'term_total')]) }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(!empty(data_get($invest, 'profits')))
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <h5 class="nk-block-title">{{ __('Transactions') }}</h5>
            </div>
            <div class="card card-bordered">
                <table class="nk-plan-tnx table">
                    <thead class="thead-light">
                    <tr>
                        <th class="tb-col-type"><span class="overline-title">{{ __('Details') }}</span></th>
                        <th class="tb-col-date tb-col-sm"><span class="overline-title">{{ __('Date & Time') }}</span></th>
                        <th class="tb-col-amount tb-col-end"><span class="overline-title">{{ __('Amount') }}</span></th>
                        <th class="tb-col-paid tb-col-end" style="width: 20px"><em class="icon ni ni-info nk-tooltip small text-soft" title="{{ __("The profit transfered into account balance or not.") }}"></em></th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr>
                        <td class="tb-col-type"><span class="sub-text">{{ __("Investment") }}</span></td>
                        <td class="tb-col-date tb-col-sm">
                            <span class="sub-text">{{ show_date(data_get($invest, 'order_at'), true) }}</span>
                        </td>
                        <td class="tb-col-amount tb-col-end"><span class="lead-text text-danger">- {{ amount_z(data_get($invest, 'amount'), $currency) }}</span></td>
                        <td class="tb-col-paid tb-col-end"><span class="sub-text"><em class="icon ni ni-info nk-tooltip text-soft" title="{{ __("Received from :account", ['account' => w2n(data_get($invest, 'payment_source')) ]) }}"></em></span></td>
                    </tr>

                    @foreach(data_get($invest, 'profits') as $profit)
                    <tr>
                        <td class="tb-col-type"><span class="sub-text">{{ __("Profit Earn - :rate", ['rate' => (($profit->type=='F') ? $profit->rate . ' '.$currency . ' ('.$profit->type.')' : $profit->rate . '%')]) }}</span></td>
                        <td class="tb-col-date tb-col-sm">
                            <span class="sub-text">{{ show_date(data_get($profit, 'calc_at'), true) }}</span>
                        </td>
                        <td class="tb-col-amount tb-col-end"><span class="lead-text">+ {{ amount_z($profit->amount, $currency, ['dp' => 'calc']) }}</span></td>
                        <td class="tb-col-paid tb-col-end">
                            <span class="sub-text">{!! ($profit->payout) ? '<em class="icon ni ni-info nk-tooltip text-soft" title="'. __("Batch #:id", ['id' => $profit->payout]). '"></em> ' : '' !!}</span>
                        </td>
                    </tr>
                    @endforeach

                    @if(data_get($invest, 'scheme.capital') && $invest->status==InvestmentStatus::COMPLETED)
                    <tr>
                        <td class="tb-col-type"><span class="sub-text">{{ __("Captial Return") }}</span></td>
                        <td class="tb-col-date tb-col-sm">
                            <span class="sub-text">{{ show_date(data_get($invest, 'updated_at'), true) }}</span>
                        </td>
                        <td class="tb-col-amount tb-col-end"><span class="lead-text">+ {{ amount_z(data_get($invest, 'amount'), $currency) }}</span></td>
                        <td class="tb-col-paid tb-col-end"><span class="sub-text"><em class="icon ni ni-info nk-tooltip text-soft" title="{{ __("Add to :account", ['account' => w2n(data_get($invest, 'payment_dest')) ]) }}"></em></span></td>
                    </tr>
                    @endif

                    </tbody>
                </table>
            </div>
            @if($invest->status === InvestmentStatus::ACTIVE && $invest->payout_type === SchemePayout::AFTER_MATURED)
                <div class="notes mt-2">
                    <div class="alert-note is-plain text-danger">
                        <em class="icon ni ni-alert"></em>
                        @if (data_get($invest, 'term_count') == data_get($invest, 'term_total'))
                            <p>{{ __('This investment plan has been matured, you will be get paid very soon.') }}</p>
                        @else
                            <p>{{ __('You will be paid once this investment plan is matured.') }}</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        const routes = {
            cancelled: "{{ route('user.investment.invest.cancel', ['id' => the_hash($invest->id)]) }}"
        },
        msgs = {
            cancelled: {
                title: "{{ __('Cancel Investment?') }}",
                btn: {cancel: "{{ __('No') }}", confirm: "{{ __('Yes, Cancel') }}"},
                context: "{!! __("You cannot revert back this action, so please confirm that you want to cancel.") !!}",
                custom: "danger", type: "warning"
            }
        }
    </script>
@endpush
