@php
    use App\Enums\SchemeStatus as SStatus;
    use App\Enums\InterestRateType as IRType;
@endphp

<div class="row g-gs">
@if(!blank($schemes))
    @foreach($schemes as $scheme)
    <div class="col-lg-4 col-sm-6">
        <div class="card card-bordered plan-card">
            <div class="card-inner-group">
                <div class="card-inner">
                    <div class="card-title-group align-start">
                        <div class="card-title">
                            <h5 class="title align-center" id="{{ the_hash($scheme->id) }}">{{ data_get($scheme, 'name') }} <span class="ml-2 badge badge-pill badge-dim {{ data_get($scheme, 'status_badge_class') }}">{{ ucfirst(data_get($scheme, 'status')) }}</span></h5>
                            <p>{{ data_get($scheme, 'desc') }}</p>
                        </div>
                        <div class="card-tools mt-n1 mr-n1">
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <ul class="link-list-opt no-bdr">
                                        <li><a href="javascript:void(0)" class="m-ivs-scheme" data-action="edit" data-view="modal" data-backdrop="static" data-uid="{{ the_hash($scheme->id) }}"><em class="icon ni ni-edit-fill"></em><span>{{ __('Update Scheme') }}</span></a></li>
                                        @if(data_get($scheme, 'status') != SStatus::ACTIVE)
                                        <li><a href="javascript:void(0)" class="m-ivs-update" data-action="{{ SStatus::ACTIVE }}" data-uid="{{ the_hash($scheme->id) }}"><em class="icon ni ni-spark-fill"></em><span>{{ __('Mark Active') }}</span></a></li>
                                        @endif

                                        @if(data_get($scheme, 'status') != SStatus::INACTIVE)
                                        <li><a href="javascript:void(0)" class="m-ivs-update" data-action="{{ SStatus::INACTIVE }}" data-uid="{{ the_hash($scheme->id) }}"><em class="icon ni ni-spark-off-fill"></em><span>{{ __('Mark Inactive') }}</span></a></li>
                                        @endif

                                        @if(data_get($scheme, 'status') != SStatus::ARCHIVED)
                                        <li><a href="javascript:void(0)" class="m-ivs-update" data-action="{{ SStatus::ARCHIVED }}" data-uid="{{ the_hash($scheme->id) }}"><em class="icon ni ni-archive-fill"></em><span>{{ __('Mark Archive') }}</span></a></li>
                                        @endif

                                        <li><a href="javascript:void(0)" class="m-ivs-update" data-action="{{ SStatus::DELETE  }}" data-uid="{{ the_hash($scheme->id) }}"><em class="icon ni ni-trash-fill"></em><span>{{ __('Delete Scheme') }}</span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-inner">
                    <div class="row">
                        <div class="col-6">
                            <div class="plan-sum">
                                <div class="amount">
                                    {{ data_get($scheme, 'rate') }}@if(data_get($scheme, 'rate_type') == IRType::PERCENT) % @else {{ base_currency() }} @endif </div>
                                <span class="title">{{ __('Interest (:type)', ['type' => (data_get($scheme, 'rate_type') == IRType::PERCENT) ? 'P' : 'F']) }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="plan-sum">
                                <div class="amount">{{ data_get($scheme, 'term') }}</div>
                                <span class="title">{{ __(':unit (Term)', ['unit' => ucfirst(data_get($scheme, 'term_type'))]) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-inner">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="plan-info">
                                <span class="title">{{ __('Capital Return') }}</span>
                                <span class="info">@if(!empty(data_get($scheme, 'capital'))) {{ __('Full / End of Term') }} @else {{ __('Partial / Each Term') }} @endif</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="plan-info">
                                <span class="title">{{ __('Profit Adjust') }}</span>
                                <span class="info">{{ __(":Type (:term)", ['type' => ucfirst(data_get($scheme, 'calc_period')), 'term' => str_replace('_', ' ', ucfirst(data_get($scheme, 'payout'))) ]) }}</span>
                            </div>
                        </div>

                        @if(!empty(data_get($scheme, 'is_fixed')))
                        <div class="col-6">
                            <div class="plan-info">
                                <span class="title">{{ __('Amount') }}</span>
                                <span class="info">{{ money(data_get($scheme, 'amount'), base_currency(), ["dp" => 'calc']) }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="plan-info">
                                <span class="title">{{ __('Investment') }}</span>
                                <span class="info">{{ __('Fixed Type') }}</span>
                            </div>
                        </div>
                        @else
                        <div class="col-6">
                            <div class="plan-info">
                                <span class="title">{{ __('Minimum') }}</span>
                                <span class="info">{{ money(data_get($scheme, 'amount'), base_currency(), ["dp" => 'calc']) }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="plan-info">
                                <span class="title">{{ __('Maximum') }}</span>
                                <span class="info">{{ money(data_get($scheme, 'maximum'), base_currency(), ["dp" => 'calc']) }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endif
</div>
