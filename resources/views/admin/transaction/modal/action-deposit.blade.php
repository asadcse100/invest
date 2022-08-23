@php 

use \App\Enums\TransactionType;
use \App\Enums\TransactionStatus;

$amount = $transaction->amount;
$currency = $transaction->currency;

$tnx_amount = $transaction->tnx_amount;
$tnx_currency = $transaction->tnx_currency;

$user_amount = money($transaction->tnx_total, $transaction->tnx_currency, ['dp' => 'calc']);

if($transaction->tnx_currency!=base_currency()) {
    $user_amount = money($transaction->tnx_total, $transaction->tnx_currency, ['dp' => 'calc']) . ' ('. money($transaction->total, $transaction->currency, ['dp' => 'calc']). ')';
}

@endphp

<div class="nk-modal-title">
	<h5 class="title mb-3">{!! __('Deposit ID# :orderid', ['orderid' => '<span class="text-primary">'.the_tnx($transaction->tnx).'</span>' ]) !!}</h5>
	<p class="caption-text">{!! __("The amount of :amount to :type via :Method.", ['type' => '<span class="fw-bold text-dark">'.ucfirst($type).'</span>', 'method' => $transaction->method_name, 'amount' => '<span class="fw-bold text-dark text-nowrap">'.$user_amount.'</span>' ]) !!}</p>
    @if (data_get($transaction, 'tnx_fees')) 
        <p class="text-dark small">* {!! __("Processing fee of :fee included in payment amount.", ['amount' => '<strong>'.money(data_get($transaction, 'tnx_total'), $tnx_currency, ['dp' => 'calc']).'</strong>', 'fee' => '<strong>'.money(data_get($transaction, 'tnx_fees'), $tnx_currency, ['dp' => 'calc']).'</strong>']) !!}</p>        
    @endif
</div>
<div class="nk-block">
    <form action="{{ route('admin.transaction.update', ['action' => 'approve', 'uid' => the_hash($transaction->id)]) }}" data-action="update">
        <div class="row gy-3">
        	<div class="col-sm-6">
        		<div class="form-group">
					<label class="form-label">{{ __('Payment Amount') }}</label>
                    <div class="form-control-wrap">
                    	<div class="form-text-hint"><span class="overline-title">{{ $tnx_currency }}</span></div>
                        <input type="text" value="{{ to_num(data_get($transaction, 'tnx_total')) }}" class="form-control" readonly="">
                    </div>
                    <div class="form-note">
                    	{{ __('The payment amount that you received.') }}
                    </div>
                </div>
        	</div>
            <div class="col-sm-6">
        		<div class="form-group">
					<label class="form-label">{{ __('Amount to :Calc', ['calc' => $transaction->calc]) }}</label>
                    <div class="form-control-wrap">
                    	<div class="form-text-hint"><span class="overline-title">{{ $currency }}</span></div>
                        <input type="text" value="{{ $amount }}" class="form-control" readonly="">
                    </div>
                    <div class="form-note">
                    	{{ __('The amount that ajdust with balance.') }}
                    </div>
                </div>
        	</div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label justify-between align-center" for="reference">{{ __('Reference / Hash') }}</label>
                    <div class="form-control-wrap">
                        <input type="text" name="reference" class="form-control" id="reference" value="{{ $transaction->reference }}" placeholder="{{ __('Reference or Hash') }}" maxlength="190">
                    </div>
                    <div class="form-note">
                    	{{ __('The reference will display to user.') }}
                    </div>
                </div>
            </div>
        	<div class="col-md-6">
                <div class="form-group">
                    <label class="form-label justify-between align-center" for="payfrom">{{ __('Received From') }}</label>
                    <div class="form-control-wrap">
                        <input type="text" name="payfrom" class="form-control" id="payfrom" value="{{ $transaction->pay_from }}" placeholder="{{ __('Receiving account name or id') }}" maxlength="190">
                    </div>
                    <div class="form-note">
                    	{{ __('Helps to identify the payment (Admin).') }}
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label justify-between align-center" for="remarks">{{ __('Note / Remarks') }}</label>
                    <div class="form-control-wrap">
                        <input type="text" name="remarks" class="form-control" id="remarks" placeholder="{{ __('Enter remarks / note') }}" maxlength="190">
                        <input type="hidden" value="{{ $transaction->tnx }}" name="orderid">
                        <input type="hidden" value="{{ TransactionStatus::COMPLETED }}" name="status">
                    </div>
                    <div class="form-note">
                    	{{ __('The note or remarks help to reminder. Only administrator can read from transaction details.') }}
                    </div>
                </div>
            </div>
            <div class="col-12">
            	<p>{!! __("Please confirm that you want to APPROVE this :type request.", ['type' => '<span class="fw-bold text-dark">'.strtoupper($type).'</span>']) !!}</p>
            </div>
        </div>
        <ul class="align-center flex-nowrap gx-2 pt-4 pb-2">
            <li>
                <button type="button" class="btn btn-primary m-tnx-update" data-confirm="yes" data-state="{{ TransactionStatus::COMPLETED }}">{{ __('Confirmed Deposit') }}</button>
            </li>
            <li>
                <button data-dismiss="modal" type="button" class="btn btn-trans btn-light">{{ __('Cancel') }}</button>
            </li>
        </ul>
        <div class="divider md stretched"></div>
        <div class="notes">
            <ul>
                <li class="alert-note is-plain">
                    <em class="icon ni ni-info"></em>
                    <p>{{ __("The deposit amount will adjust into user account once you approved.") }}</p>
                </li>
                <li class="alert-note is-plain text-danger">
                    <em class="icon ni ni-alert"></em>
                    <p>{{ __("You can not undo this action once you you confirm and approved.") }}</p>
                </li>
            </ul>
        </div>
    </form>
</div>