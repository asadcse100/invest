@php
   $details = data_get($transaction, 'details');
@endphp
<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
        <div class="modal-body modal-body-md">
            <div class="nk-modal-head mb-3 mb-sm-5">
                <h4 class="nk-modal-title title">{{ __('Transaction') }} <small class="text-primary">#{{ data_get($details, 'order_id') }}</small></h4>
            </div>
            <div class="nk-block">
                <div class="nk-block-between flex-wrap g-3">
                    <div class="nk-tnx">
                        @if(data_get($transaction, 'type') == \App\Enums\TransactionType::WITHDRAW)
                            <div class="nk-tnx-icon bg-warning text-white">
                                <em class="icon ni ni-arrow-up-right"></em>
                            </div>
                        @else
                            <div class="nk-tnx-icon bg-success text-white">
                                <em class="icon ni ni-arrow-down-left"></em>
                            </div>
                        @endif
                        <div class="nk-tnx-text">
                            <h5 class="title">{{ data_get($details, 'symbol') }}  {{ data_get($details, 'amount') }}</h5>
                            <span class="sub-text mt-n1">{{ data_get($details, 'order_date') }}</span>
                        </div>
                    </div>
                    <ul class="align-center flex-wrap gx-3">
                        <li>
                            <span class="badge badge-sm {{ $transaction->status == \App\Enums\TransactionStatus::COMPLETED ? 'badge-success' : 'badge-primary' }}">{{ data_get($details, 'status') }}</span>
                        </li>
                    </ul>
                </div>
                <div class="nk-modal-head mt-sm-5 mt-4 mb-4">
                    <h5 class="title">{{ __('Transaction Info') }}</h5>
                </div>
                <div class="row gy-3">
                    <div class="col-lg-6">
                        <span class="sub-text">{{ __('Order ID') }}</span>
                        <span class="caption-text">{{ data_get($details, 'order_id') }}</span>
                    </div>
                    <div class="col-lg-6">
                        <span class="sub-text">{{ __('Order Date') }}</span>
                        <span class="caption-text text-break">{{ data_get($details, 'order_date')}}</span>
                    </div>
                    <div class="col-lg-6">
                        <span class="sub-text">{{ __('Amount') }}</span>
                        <span class="caption-text">{{ data_get($details, 'amount') }}</span>
                    </div>
                    <div class="col-lg-6">
                        <span class="sub-text">{{ __('Exchange Rate') }}</span>
                        <span class="caption-text">1 {{ data_get($details, 'base_currency') }} = {{ data_get($details, 'exchange_rate') }}</span>
                    </div>

                    <div class="col-lg-6">
                        <span class="sub-text">{{ __('Total Charge') }}</span>
                        <span class="caption-text fw-bold">{{ data_get($details, 'total') }}</span>
                    </div>
                </div>
                <div class="nk-modal-head mt-sm-5 mt-4 mb-4">
                    <h5 class="title">{{ __('Transaction Details') }}</h5>
                </div>
                <div class="row gy-3">
                    <div class="col-lg-6">
                        <span class="sub-text">{{ __('Transaction Type') }}</span>
                        <span class="caption-text">{{ data_get($details, 'type') }}</span>
                    </div>
                    <div class="col-lg-6">
                        <span class="sub-text">{{ __('Payment Gateway') }}</span>
                        <span class="caption-text align-center">{{ data_get($details, 'gateway') }}
                            @if(data_get($details, 'is_online') == 1)
                                <span class="badge badge-primary ml-2 text-white">{{ __('Online Gateway') }}</span>
                            @endif
                        </span>
                    </div>

                    @if (data_get($details, 'pay_from'))
                    <div class="col-lg-6">
                        <span class="sub-text">{{ __('Payment From') }}</span>
                        <span class="caption-text text-break">{{ data_get($details, 'pay_from') }}</span>
                    </div>
                    @endif

                    @if (data_get($details, 'pay_to'))
                    <div class="col-lg-6">
                        <span class="sub-text">{{ __('Payment To') }}</span>
                        <span class="caption-text text-break">{{ data_get($details, 'pay_to') }}</span>
                    </div>
                    @endif

                    @if (data_get($details, 'reference'))
                    <div class="col-lg-12">
                        <span class="sub-text">{{ __('Transaction Reference') }}</span>
                        <span class="caption-text text-break">{{ data_get($details, 'reference') }}</span>
                    </div>
                    @endif
                    
                    <div class="col-lg-12">
                        <span class="sub-text">{{ __('Details') }}</span>
                        <span class="caption-text">{{ data_get($details, 'details') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
