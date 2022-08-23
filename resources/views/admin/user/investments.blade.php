@php 
use \App\Enums\TransactionType as TType;
use \App\Enums\TransactionCalcType as TCType;

$base_currency = base_currency();

$investments = data_get($user,'allInvested');
$statements = data_get($user,'ivStatements');
@endphp

@section('title', __("Investments"))

<div class="nk-block-head">
    <div class="nk-block-between g-3">
        <div class="nk-block-head-content">
            <h5 class="title">{{ __('Invested Plans') }}</h5>
            <p>{{ __('Recent :num invested plans made by user.',['num' => 10]) }}</p>
        </div>
        <div class="nk-block-head-content">
            <a href="{{ route('admin.investment.list',['user' => the_uid($user->id)]) }}">{{ __('View All') }}</a>
        </div>
    </div>
</div>

<div class="nk-block is-stretch">
    <div class="nk-tb-list nk-tb-ivx{{ user_meta('iv_invest_display') == 'compact' ? ' is-compact': '' }}">
        <div class="nk-tb-item nk-tb-head">
            <div class="nk-tb-col"><span>{{ __('Plan') }}</span></div>
            <div class="nk-tb-col tb-col-lg"><span>{{ __('Start Date') }}</span></div>
            <div class="nk-tb-col tb-col-lg"><span>{{ __('End Date') }}</span></div>
            <div class="nk-tb-col tb-col-lg"><span>{{ __('Investment ID') }}</span></div>
            <div class="nk-tb-col"><span>{{ __('Amount') }}</span></div>
            <div class="nk-tb-col tb-col-sm"><span>{{ __('Status') }}</span></div>
            <div class="nk-tb-col"><span>&nbsp;</span></div>
        </div>

        @forelse($investments as $plan)
            <div class="nk-tb-item">
                <div class="nk-tb-col">
                    <div class="align-center">
                        <div class="user-avatar user-avatar-sm bg-light">
                            <span>{{ strtoupper(substr(data_get($plan, 'scheme.short'), 0, 2)) }}</span>
                        </div>
                        <span class="tb-sub ml-2">{{ data_get($plan, 'scheme.name') }} <span class="d-none d-md-inline">- {{ data_get($plan, 'calc_details_alter') }}</span></span>
                    </div>
                </div>
                <div class="nk-tb-col tb-col-lg">
                    <span class="tb-sub">{{ show_date(data_get($plan, 'term_start'), true) }}</span>
                </div>
                <div class="nk-tb-col tb-col-lg">
                    <span class="tb-sub">{{ show_date(data_get($plan, 'term_end'), true) }}</span>
                </div>
                <div class="nk-tb-col tb-col-lg">
                    <span class="tb-sub">{{ data_get($plan, 'ivx') }}</span>
                </div>
                <div class="nk-tb-col">
                    <span class="tb-sub tb-amount">{{ money(data_get($plan, 'amount'), base_currency()) }}</span>
                </div>
                <div class="nk-tb-col tb-col-sm">
                    @if(data_get($plan, 'status')=='active')
                    <div class="progress progress-sm w-100px nk-tooltip" title="{{ __("Received :amount (:percent)", ['amount' => money(data_get($plan, 'received', 0), base_currency()), 'percent' => data_get($plan, 'progress', 0).'%']) }}">
                        <div class="progress-bar" data-progress="{{ data_get($plan, 'progress', 0) }}"></div>
                    </div>
                    @else 
                    <span class="badge badge-dim {{ the_state(data_get($plan, 'status'), ['prefix' => 'badge']) }}">{{ __(ucfirst(data_get($plan, 'status'))) }}</span>
                    @endif
                </div>
                <div class="nk-tb-col nk-tb-col-action">
                    <a href="{{ route('admin.investment.details', ['id' => the_hash($plan->id)]) }}" target="_blank" class="text-soft btn btn-sm btn-icon btn-trigger"><em class="icon ni ni-chevron-right"></em></a>
                </div>    
            </div>
        @empty
            <div class="nk-tb-item">
                <div class="nk-tb-col">{{ __('No transactions history found!') }}</div>
            </div>
        @endforelse
    </div>
</div>

<div class="nk-block-head">
    <div class="nk-block-between g-3">
        <div class="nk-block-head-content">
            <h5 class="title">{{ __('Invested Statements') }}</h5>
            <p>{{ __('Recent :num investment transactions.',['num' => 15]) }}</p>
        </div>
        <div class="nk-block-head-content">
            <a href="{{ route('admin.investment.transactions.list',['user' => the_uid($user->id)]) }}">{{ __('View All') }}</a>
        </div>
    </div>
</div>

<div class="nk-block is-stretch is-compact">
    <div class="nk-tb-list nk-tb-ivx{{ user_meta('iv_invest_display') == 'compact' ? ' is-compact': '' }}">
        <div class="nk-tb-item nk-tb-head">
            <div class="nk-tb-col"><span>{{ __('Statement ID') }}</span></div>
            <div class="nk-tb-col tb-col-lg"><span>{{ __('Date & Time') }}</span></div>
            <div class="nk-tb-col tb-col-lg"><span>{{ __('Details') }}</span></div>
            <div class="nk-tb-col tb-col-lg"><span>{{ __('Type') }}</span></div>
            <div class="nk-tb-col text-right"><span>{{ __('Amount') }}</span></div>
        </div>
        @forelse($statements as $statement)
            <div class="nk-tb-item" id="tnx-row-{{ $statement->id }}">
                @include('investment.admin.statement.transaction-row', ['transaction' => $statement, 'hide_user'=>true])
            </div>
        @empty
            <div class="nk-tb-item">
                <div class="nk-tb-col">{{ __('No transactions history found!') }}</div>
            </div>
        @endforelse
    </div>
</div>