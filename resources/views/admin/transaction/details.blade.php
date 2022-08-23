@extends('admin.layouts.master')
@section('title', __('Transaction List'))

@php
   use \App\Enums\TransactionType as dTType;
   use \App\Enums\TransactionStatus as dTStatus;
   use \App\Enums\TransactionCalcType as dTCType;

   $tnx = (!empty($transaction)) ? $transaction : false;
   $user = (!empty($profile)) ? $profile : false;
@endphp

@section('content')
    <div class="nk-content-body">
    	<div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between g-3">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">{{ __('Transaction') }} / <span class="text-primary">#{{ the_tnx(data_get($tnx, 'tnx')) }}</span></h3>
                    <div class="nk-block-des text-soft">
                        <ul class="list-inline">
                            <li>{{ __('Type:') }} <span class="text-base">{{ __(ucfirst($tnx->type)) }}</span></li>
                            <li>{{ __('Status:') }} <span class="text-base">{{ __(ucfirst($tnx->status)) }}</span></li>
                            <li>{{ __('Order At:') }} <span class="text-base">{{ __(show_date($tnx->created_at, true)) }}</span></li>
                        </ul>
                    </div>
                </div>
                <div class="nk-block-head-content">
                    <a href="{{ route('admin.transactions.list') }}" class="btn btn-outline-light bg-white d-none d-sm-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{ __('Back') }}</span></a>
                    <a href="{{ route('admin.transactions.list') }}" class="btn btn-icon btn-outline-light bg-white d-inline-flex d-sm-none"><em class="icon ni ni-arrow-left"></em></a>
                </div>
            </div>
        </div>
        {{-- nk-block-head --}}

        @if(!empty($tnx))
        @php
        	$base_currency = base_currency();

	        $amount = $tnx->amount;
	        $total = $tnx->total;
	        $currency = $tnx->currency;

	        $tnx_currency = $tnx->tnx_currency;
	        $tnx_amount = $tnx->tnx_amount;
	        $tnx_total = $tnx->tnx_amount;
	        $exchange = $tnx->exchange;

	        $completed_by = data_get($tnx, 'completed_by');
	    @endphp
        <div class="card card-bordered card-stretch">
            <div class="card-inner">
				<div class="nk-block">
		            <div class="row gy-3">
		                <div class="col-md-6">
		                    <h6 class="overline-title">{{ __('In Account') }}</h6>
		                    <div class="row gy-2">
		                        <div class="col-12">
		                            <span class="sub-text">{{ __('Amount') }}</span>
		                            <span class="caption-text">{{ money($amount, $base_currency) }}</span>
		                        </div>
		                        <div class="col-12">
		                            <span class="sub-text">{{ __('Total :Type', ['type' => data_get($tnx, 'type')]) }}</span>
		                            <span class="caption-text fw-bold">{{ money($total, $base_currency) }}</span>
		                        </div>
		                        <div class="col-12">
		                            <span class="sub-text">{{ __('Fees') }}</span>
		                            <span class="caption-text">{{ money(data_get($tnx, 'fees', '-'), $base_currency) }}</span>
		                        </div>
		                    </div>
		                </div>
		                <div class="col-md-6">
		                    <h6 class="overline-title">{{ __('In Transaction') }}</h6>
		                    <div class="row gy-2">
		                        <div class="col-12">
		                            <span class="sub-text">{{ __('Amount') }}</span>
		                            <span class="caption-text">{{ money($tnx_amount, $tnx_currency) }}</span>
		                        </div>
		                        <div class="col-12">
		                            <span class="sub-text">{{ __('Total Charge') }}</span>
		                            <span class="caption-text fw-bold">{{ money(data_get($tnx, 'tnx_total', '-'), $tnx_currency) }}</span>
		                        </div>
		                        <div class="col-12">
		                            <span class="sub-text">{{ __('Exchange Rate') }}</span>
		                            <span class="caption-text">{{ __('1 :from = :rate', ['rate' => money($exchange, $tnx_currency), 'from' => $currency]) }}</span>
		                        </div>
		                    </div>
		                </div>
		            </div>

		            <div class="divider md stretched"></div>
		            <h6 class="title">{{ __('Order Info') }}</h6>
		            <div class="row gy-3">
		                <div class="col-md-6">
		                    <span class="sub-text">{{ __('Order Date') }}</span>
		                    <span class="caption-text text-break">{{ show_date(data_get($tnx, 'created_at')) }}</span>
		                </div>
		                <div class="col-md-6">
		                    <span class="sub-text">{{ __('Order By') }}</span>
		                    <span class="caption-text">
		                        {{ the_uid($tnx->transaction_by->id) }}
		                        <span class="small text-soft nk-tooltip" title="{{ str_protect($tnx->transaction_by->username, 4) . ' ('.str_protect($tnx->transaction_by->email).')' }}"><em class="icon ni ni-info-fill"></em></span>
		                    </span>
		                </div>
		                @if(data_get($tnx, 'confirmed_at'))
		                <div class="col-md-6">
		                    <span class="sub-text">{{ __('Confirmed At') }}</span>
		                    <span class="caption-text text-break">{{ show_date(data_get($tnx, 'confirmed_at'), true) }}</span>
		                </div>
		                <div class="col-md-6">
		                    <span class="sub-text">{{ __('Confirmed By') }}</span>
		                    <span class="caption-text">{{ data_get($tnx, 'confirmed_by', 'Not yet') }}</span>
		                </div>
		                @endif
		                @if(data_get($tnx, 'completed_at'))
		                <div class="col-md-6">
		                    <span class="sub-text">{{ __('Completed At') }}</span>
		                    <span class="caption-text text-break">{{ show_date(data_get($tnx, 'completed_at'), true) }}</span>
		                </div>

		                <div class="col-md-6">
		                    <span class="sub-text">{{ __('Completed By') }}</span>
		                    <span class="caption-text">{!! (isset($completed_by['name']) ? $completed_by['name'] : '<em class="text-soft small">'. __('Unknown') .'</em>') !!}</span>
		                </div>
		                @endif
		            </div>

		            <div class="divider md stretched"></div>
		            <h6 class="title">{{ __('Additional Details') }}</h6>
		            <div class="row gy-3">
		                <div class="col-lg-6">
		                    <span class="sub-text">{{ __('Transaction Type') }}</span>
		                    <span class="caption-text">{{ ucfirst(data_get($tnx, 'type')) }}</span>
		                </div>
		                <div class="col-lg-6">
		                    <span class="sub-text">{{ __('Payment Gateway') }}</span>
		                    <span class="caption-text align-center">{{ data_get($tnx, 'tnx_method') }}
		                        @if(data_get($tnx, 'is_online') == 1)
		                            <span class="badge badge-primary ml-2 text-white">{{ __('Online Gateway') }}</span>
		                        @endif
		                    </span>
		                </div>

		                @if(data_get($tnx, 'pay_from'))
		                <div class="col-lg-6">
		                    <span class="sub-text">{{ __('Payment From') }}</span>
		                    <span class="caption-text text-break"><span class="small">{{ data_get($tnx, 'pay_from', '~') }}</span></span>
		                </div>
		                @endif

		                @if(data_get($tnx, 'reference'))
		                <div class="col-lg-6">
		                    <span class="sub-text">{{ __('Reference / Hash') }}</span>
		                    <span class="caption-text text-break">{{ data_get($tnx, 'reference', '~') }}</span>
		                </div>
		                @endif

		                @if(data_get($tnx, 'pay_to'))
		                <div class="col-lg-6">
		                    <span class="sub-text">{{ __('Payment To') }} <small>({{ data_get($tnx, 'meta.pay_meta.account_name') ?? data_get($tnx, 'meta.pay_meta.payment.acc_name') }})</small></span>
		                    <span class="caption-text text-break"><span class="small">{{ data_get($tnx, 'pay_to', '~') }}</span></span>
		                </div>
		                @endif

		                @if(data_get($tnx, 'description'))
		                <div class="col-lg-12">
		                    <span class="sub-text">{{ __('Transaction Details') }}</span>
		                    <span class="caption-text">{{ data_get($tnx, 'description') }}</span>
		                </div>
		                @endif

						@if(data_get($tnx, 'meta.unote'))
		                <div class="col-lg-12">
		                    <span class="sub-text">{{ __('Description by User') }}</span>
		                    <span class="caption-text">{{ data_get($tnx, 'meta.unote') }}</span>
		                </div>
		                @endif

		                @if(data_get($tnx, 'note'))
				        <div class="col-lg-12">
				            <span class="sub-text">{{ __('Admin Note for User') }}</span>
				            <span class="caption-text">{{ data_get($tnx, 'note') }}</span>
				        </div>
				        @endif

		                @if(data_get($tnx, 'remark'))
		                <div class="col-lg-12">
		                    <span class="sub-text">{{ __('Remarks by Admin') }}</span>
		                    <span class="caption-text">{{ data_get($tnx, 'remark') }}</span>
		                </div>
		                @endif

						@if(data_get($tnx->ledger,'balance'))
						<div class="col-lg-6">
							<span class="sub-text">{{ __('Updated Balance') }}</span>
							<span class="caption-text">{{ money(data_get($tnx->ledger, 'balance'),base_currency()) }}</span>
						</div>
						@endif
		            </div>
		        </div>
                {{-- .nk-block --}}
            </div>
        </div>
        @else
        Not found!
        @endif

    </div>
@endsection
