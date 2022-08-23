@php

use \App\Enums\TransactionType;
use \App\Enums\TransactionStatus;

$amount = $transaction->total;
$currency = $transaction->currency;
$tnx_amount = $transaction->tnx_amount;
$tnx_currency = $transaction->tnx_currency;
$user_amount = money($transaction->tnx_amount, $transaction->tnx_currency, ['dp' => 'calc']);

if($transaction->tnx_currency!=base_currency()) {
    $user_amount = money($transaction->tnx_amount, $transaction->tnx_currency, ['dp' => 'calc']) . ' ('. money($transaction->amount, $transaction->currency, ['dp' => 'calc']). ')';
}

@endphp

<div class="nk-modal-title">
	<h5 class="title">{!! __('Withdraw ID# :orderid', ['type'=> $type, 'orderid' => '<span class="text-primary">'.the_tnx($transaction->tnx).'</span>' ]) !!}</h5>
</div>

<div class="nk-block">
	<p>{!! __("User (:name) request to withdraw :amount via :Method. Please check out the details and send payment to user account below.", ['method' => '<span class="fw-bold text-dark">'.$transaction->method_name.'</span>', 'amount' => '<span class="fw-bold text-dark text-nowrap">'.$user_amount.'</span>', 'name' => '<span class="fw-bold text-dark">'.the_uid($transaction->customer->id).'</span>' ]) !!}</p>

    <div class="divider md stretched"></div>
	<table class="table table-plain table-borderless table-sm mb-0">
		<tr>
			<td><span class="sub-text">{{ __("Withdraw Amount") }}</span></td>
			<td>
				<span class="lead-text">{{ money($tnx_amount, $tnx_currency, ['dp' => 'calc']) }}</span> 
				@if (data_get($transaction, 'tnx_fees')) 
			    <span class="small text-soft">
			    	{!! __("Total withdraw :amount including fees :fee.", ['amount' => '<strong>'.money(data_get($transaction, 'tnx_total'), $tnx_currency, ['dp' => 'calc']).'</strong>', 'fee' => '<strong>'.money(data_get($transaction, 'tnx_fees'), $tnx_currency, ['dp' => 'calc']).'</strong>']) !!}
			    </span>
			    @endif
			</td>
		</tr>
		<tr>
			<td><span class="sub-text">{{ __("Withdraw Method") }}</span></td>
			<td><span class="lead-text">{{ $transaction->method_name }}</span></td>
		</tr>
		<tr>
			<td><span class="sub-text">{{ __("Withdraw Account") }}</span></td>
			<td><span class="lead-text">{{ data_get($transaction, 'meta.pay_meta.label') }}</span></td>
		</tr>

		<tr>
			<td><span class="sub-text">{{ __("Payment Information") }}</span></td>
			<td>
				@if(data_get($transaction, 'tnx_method') === 'wd-bank-transfer')
				<table class="table table-plain table-borderless table-sm mb-0">
					@if (data_get($transaction, 'meta.pay_meta.payment.acc_type'))
					<tr>
						<td><span class="sub-text">{{ __('Account Type') }}</span></td>
						<td><span class="lead-text">{{ ucfirst(data_get($transaction, 'meta.pay_meta.payment.acc_type')) }}</span></td>
					</tr>
					@endif

					@if (data_get($transaction, 'meta.pay_meta.payment.acc_name'))
					<tr>
						<td><span class="sub-text">{{ __('Account Name') }}</span></td>
						<td><span class="lead-text">{{ ucfirst(data_get($transaction, 'meta.pay_meta.payment.acc_name')) }}</span></td>
					</tr>
					@endif

					@if (data_get($transaction, 'meta.pay_meta.payment.acc_no'))
					<tr>
						<td><span class="sub-text">{{ __('Account Number') }}</span></td>
						<td><span class="lead-text">{{ ucfirst(data_get($transaction, 'meta.pay_meta.payment.acc_no')) }}</span></td>
					</tr>
					@endif

					@if (data_get($transaction, 'meta.pay_meta.currency'))
					<tr>
						<td><span class="sub-text">{{ __('Account Currency') }}</span></td>
						<td><span class="lead-text">{{ get_currency(data_get($transaction, 'meta.pay_meta.currency'), 'name') . ' ('.data_get($transaction, 'meta.pay_meta.currency').')' }}</span></td>
					</tr>
					@endif

					@if (data_get($transaction, 'meta.pay_meta.payment.bank_name'))
					<tr>
						<td><span class="sub-text">{{ __('Bank Name') }}</span></td>
						<td><span class="lead-text">{{ data_get($transaction, 'meta.pay_meta.payment.bank_name') }}</span></td>
					</tr>
					@endif

					@if (data_get($transaction, 'meta.pay_meta.payment.bank_branch'))
					<tr>
						<td><span class="sub-text">{{ __('Branch') }}</span></td>
						<td><span class="lead-text">{{ data_get($transaction, 'meta.pay_meta.payment.bank_branch') }}</span></td>
					</tr>
					@endif

					@if (data_get($transaction, 'meta.pay_meta.payment.bank_address'))
					<tr>
						<td><span class="sub-text">{{ __('Bank Address') }}</span></td>
						<td><span class="lead-text">{{ data_get($transaction, 'meta.pay_meta.payment.bank_address') }}</span></td>
					</tr>
					@endif

					@if (data_get($transaction, 'meta.pay_meta.payment.country'))
					<tr>
						<td><span class="sub-text">{{ __('Country') }}</span></td>
						<td><span class="lead-text">{{ data_get($transaction, 'meta.pay_meta.payment.country') }}</span></td>
					</tr>
					@endif

					@if (data_get($transaction, 'meta.pay_meta.payment.sortcode'))
					<tr>
						<td><span class="sub-text">{{ __('Sort Code') }}</span></td>
						<td><span class="lead-text">{{ data_get($transaction, 'meta.pay_meta.payment.sortcode') }}</span></td>
					</tr>
					@endif

					@if (data_get($transaction, 'meta.pay_meta.payment.routing'))
					<tr>
						<td><span class="sub-text">{{ __('Routing Number') }}</span></td>
						<td><span class="lead-text">{{ data_get($transaction, 'meta.pay_meta.payment.routing') }}</span></td>
					</tr>
					@endif

					@if (data_get($transaction, 'meta.pay_meta.payment.swift'))
					<tr>
						<td><span class="sub-text">{{ __('Swift Code / BIC') }}</span></td>
						<td><span class="lead-text">{{ data_get($transaction, 'meta.pay_meta.payment.swift') }}</span></td>
					</tr>
					@endif

					@if (data_get($transaction, 'meta.pay_meta.payment.iban'))
					<tr>
						<td><span class="sub-text">{{ __('IBAN Number') }}</span></td>
						<td><span class="lead-text">{{ data_get($transaction, 'meta.pay_meta.payment.iban') }}</span></td>
					</tr>
					@endif
				</table>
				@else
					@if(data_get($transaction, 'tnx_method') === 'wd-paypal')
						@if(data_get($transaction,'meta.pay_meta.payment'))
						<div class="label lead-text">{{ __("Email Address / PayPal") }}</div>
						<div class="data mb-1">{{ data_get($transaction,'meta.pay_meta.payment') }}</div>
						@endif
					@endif

					@if (data_get($transaction, 'meta.pay_meta.currency'))
					<div class="label lead-text">{{ data_get($transaction, 'tnx_method') === 'wd-crypto' ? __("Wallet Type") : __('Account Currency') }}</div>
					<div class="data mb-1">{{ get_currency(data_get($transaction, 'meta.pay_meta.currency'), 'name') . ' ('.data_get($transaction, 'meta.pay_meta.currency').')' }}</div>
					@endif

					@if (data_get($transaction, 'meta.pay_meta.wallet'))
					<div class="label lead-text">{{ __("Wallet Address") }}</div>
					<div class="data mb-1">{{ data_get($transaction, 'meta.pay_meta.wallet') }}</div>
					@endif
				@endif
			</td>
		</tr>
		<tr>
			<td><span class="sub-text">{{ __('Amount to :Calc', ['calc' => $transaction->calc]) }}</span></td>
			<td>
				<span class="lead-text">{{ money($amount, $currency, ['dp' => 'calc']) }}</span>
				@if (data_get($transaction, 'fees'))
				<span class="small text-soft">{!! (data_get($transaction, 'fees')) ? __('Amount included processing fees of :fee', ['fee' => '<strong>'.money(data_get($transaction, 'fees'), $currency, ['dp' => 'calc'])]).'</strong>' : '' !!}
				</span>
				@endif
			</td>
		</tr>
	</table>
    <div class="divider md stretched"></div>
	<form action="{{ route('admin.transaction.update', ['action' => 'confirm', 'uid' => the_hash($transaction->id)]) }}" data-action="update">
		<p>{!! __("Please confirm that you want to PROCCED this :type request.", ['type' => '<span class="fw-bold text-dark">'.strtoupper($type).'</span>']) !!}</p>

		<ul class="align-center flex-nowrap gx-2 py-2">
            <li>
            	<input type="hidden" value="{{ $transaction->tnx }}" name="orderid">
                <input type="hidden" value="{{ TransactionStatus::CONFIRMED }}" name="status">
                <button type="button" class="btn btn-primary m-tnx-update" data-confirm="yes" data-state="{{ TransactionStatus::CONFIRMED }}">{!! __('Procced Withdraw') !!}</button>
            </li>
            <li>
                <button data-dismiss="modal" type="button" class="btn btn-trans btn-light">{{ __('Cancel') }}</button>
            </li>
        </ul>
	</form>

    <div class="divider md stretched"></div>
    <div class="notes">
        <ul>
            <li class="alert-note is-plain">
                <em class="icon ni ni-info"></em>
                <p>{{ __("You able to complete the withdraw after confirm the withdraw request.") }}</p>
            </li>
            <li class="alert-note is-plain text-danger">
                <em class="icon ni ni-alert"></em>
                <p>{{ __("User unable to cancel the withdraw request once you have confirmed.") }}</p>
            </li>
        </ul>
    </div>
</div>
