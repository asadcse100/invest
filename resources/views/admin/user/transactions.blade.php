@php 
use \App\Enums\TransactionType as TType;
use \App\Enums\TransactionCalcType as TCType;

$base_currency = base_currency();
@endphp

@section('title', __("Recent Transaction"))

<div class="nk-block-head">
    <h5 class="title">{{ __('Recent Transaction') }}</h5>
    <p>{{ __('All the recent transaction made by user.') }}</p>
</div>
<div class="nk-block is-stretch">
    <div class="nk-tb-list nk-tb-tnx">
        <div class="nk-tb-item nk-tb-head">
            <div class="nk-tb-col"><span class="overline-title">{{ __('Details') }}</span></div>
            <div class="nk-tb-col tb-col-sm"><span class="overline-title">{{ __('Type') }}</span></div>
            <div class="nk-tb-col tb-col-lg"><span class="overline-title">{{ __('Order') }}</span></div>
            <div class="nk-tb-col tb-col-md"><span class="overline-title">{{ __('Reference') }}</span></div>
            <div class="nk-tb-col text-right"><span class="overline-title">{{ __('Amount') }}</span></div>
            <div class="nk-tb-col nk-tb-col-tools">&nbsp;</div>
        </div>

        @forelse($user->transactions as $tnx)
            <div class="nk-tb-item">
                <div class="nk-tb-col">
                    <div class="nk-tnx-type">
                        <div class="nk-tnx-type-badge">
                            {!! tnx_type_icon($tnx, 'tnx-type-icon') !!}
                        </div>
                        <div class="nk-tnx-type-text">
                            <span class="tb-lead">{{ $tnx->type_of_fund }}</span>
                            <span class="nk-tnx-meta">
                                <span
                                    class="date">{{ show_date($tnx->created_at) }}</span>
                                <span
                                    class="status dot-join">{{ __(ucfirst($tnx->status)) }}</span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="nk-tb-col tb-col-sm">
                    <span class="badge badge-pill badge-sm badge-dim {{ ($tnx->type == 'deposit') ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($tnx->type) }}</span>
                </div>

                <div class="nk-tb-col tb-col-lg">
                    <span class="tb-lead-sub">{{ data_get($tnx, 'tnx') }}</span>
                </div>

                <div class="nk-tb-col tb-col-md">
                    @if(!empty($tnx->reference))
                        <span class="tb-lead-sub">{{ $tnx->reference }} <em class="icon ni ni-info text-soft fs-13px" data-toggle="tooltip" title="{{ $tnx->description }}"></em></span>
                    @else 
                        <span class="tb-lead-sub">-</span>
                    @endif
                </div>

                <div class="nk-tb-col text-right">
                    <span class="tb-amount{{ $tnx->calc == TCType::DEBIT ? ' text-danger' : '' }}">{{ $tnx->calc == TCType::CREDIT ? '+' : '-' }} {{ show_amount(data_get($tnx, 'tnx_amount'), data_get($tnx, 'tnx_currency')) }} <span>{{ data_get($tnx, 'tnx_currency') }}</span></span>
                    <span class="tb-amount-sm">{{ show_amount(data_get($tnx, 'amount'), $base_currency) }} {{ $base_currency }}</span>
                </div>

                <div class="nk-tb-col nk-tb-col-tools tnx-details">
                    <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-trigger mr-n1 m-tnx-view" data-action="view" data-view="tnx" data-uid="{{ the_hash($tnx->id) }}"><em class="icon ni ni-chevron-right"></em></a>
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
    <h5 class="title">{{ __('Misc Transaction') }}</h5>
    <p>{{ __('All the recent misc transaction made by user.') }}</p>
</div>
<div class="nk-block is-stretch">
    <div class="nk-tb-list nk-tb-tnx">
        <div class="nk-tb-item nk-tb-head">
            <div class="nk-tb-col"><span class="overline-title">{{ __('Details') }}</span></div>
            <div class="nk-tb-col tb-col-sm"><span class="overline-title">{{ __('Type') }}</span></div>
            <div class="nk-tb-col tb-col-lg"><span class="overline-title">{{ __('Order') }}</span></div>
            <div class="nk-tb-col tb-col-md"><span class="overline-title">{{ __('Reference') }}</span></div>
            <div class="nk-tb-col text-right"><span class="overline-title">{{ __('Amount') }}</span></div>
            <div class="nk-tb-col nk-tb-col-tools">&nbsp;</div>
        </div>

        @forelse($user->miscTnx as $tnx)
            <div class="nk-tb-item">
                <div class="nk-tb-col">
                    <div class="nk-tnx-type">
                        <div class="nk-tnx-type-badge">
                            {!! tnx_type_icon($tnx, 'tnx-type-icon') !!}
                        </div>
                        <div class="nk-tnx-type-text">
                            <span class="tb-lead">{{ $tnx->type_of_fund }}</span>
                            <span class="nk-tnx-meta">
                                <span
                                    class="date">{{ show_date($tnx->created_at) }}</span>
                                <span
                                    class="status dot-join">{{ __(ucfirst($tnx->status)) }}</span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="nk-tb-col tb-col-sm">
                    <span class="badge badge-pill badge-sm badge-dim {{ ($tnx->type == 'deposit') ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($tnx->type) }}</span>
                </div>

                <div class="nk-tb-col tb-col-lg">
                    <span class="tb-lead-sub">{{ data_get($tnx, 'tnx') }}</span>
                </div>

                <div class="nk-tb-col tb-col-md">
                    @if(!empty($tnx->reference))
                        <span class="tb-lead-sub">{{ $tnx->reference }} <em class="icon ni ni-info text-soft fs-13px" data-toggle="tooltip" title="{{ $tnx->description }}"></em></span>
                    @else 
                        <span class="tb-lead-sub">-</span>
                    @endif
                </div>

                <div class="nk-tb-col text-right">
                    <span class="tb-amount{{ $tnx->calc == TCType::DEBIT ? ' text-danger' : '' }}">{{ $tnx->calc == TCType::CREDIT ? '+' : '-' }} {{ show_amount(data_get($tnx, 'tnx_amount'), data_get($tnx, 'tnx_currency')) }} <span>{{ data_get($tnx, 'tnx_currency') }}</span></span>
                    <span class="tb-amount-sm">{{ show_amount(data_get($tnx, 'amount'), $base_currency) }} {{ $base_currency }}</span>
                </div>

                <div class="nk-tb-col nk-tb-col-tools tnx-details">
                    <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-trigger mr-n1 m-tnx-view" data-action="view" data-view="tnx" data-uid="{{ the_hash($tnx->id) }}"><em class="icon ni ni-chevron-right"></em></a>
                </div>
            </div>
        @empty
            <div class="nk-tb-item">
                <div class="nk-tb-col">{{ __('No transactions history found!') }}</div>
            </div>
        @endforelse
    </div>
</div>

@push('modal')
<div class="modal fade" role="dialog" id="ajax-modal"></div>
@endpush
@push('scripts')
<script type="text/javascript">
    const routes = { view: "{{ route('admin.transaction.details') }}" };
</script>
@endpush