@php 

use \App\Enums\TransactionType;
use \App\Enums\TransactionStatus;

$amount = $transaction->amount;
$currency = $transaction->currency;

$tnx_amount = $transaction->tnx_amount;
$tnx_currency = $transaction->tnx_currency;

$user_amount = money($transaction->tnx_amount, $transaction->tnx_currency);

if($transaction->tnx_currency!=base_currency()) {
    $user_amount = money($transaction->tnx_amount, $transaction->tnx_currency) . ' ('. money($transaction->amount, $transaction->currency). ')';
}

@endphp

<div class="nk-modal-title">
	<h5 class="title mb-3">{!! __('Cancellation of #:orderid', ['type'=> $type, 'orderid' => '<span class="text-primary">'.the_tnx($transaction->tnx).'</span>' ]) !!}</h5>

    @if ($type==TransactionType::REFERRAL) 
    <p class="caption-text">{!! __("Do you want to cancel this referral commission?") !!}</p>
    @else 
	<p class="caption-text">{!! __("Are you sure you want to cancel this :type request? ", ['type' => $type, 'amount' => '<span class="fw-bold text-dark text-nowrap">'.$user_amount.'</span>', 'method' => $transaction->method_name]) !!}</p>
    @endif
</div>
<div class="nk-block">
    <form action="{{ route('admin.transaction.update', ['action' => 'reject', 'uid' => the_hash($transaction->id)]) }}" data-action="update">
    	<div class="row gy-3">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label justify-between align-center" for="note">{{ __('Note for User') }} <span class="small">{{ __("Show in userend") }}</span> </label>
                    <div class="form-control-wrap">
                        <input type="text" name="note" class="form-control" id="note" placeholder="{{ __('Enter user note') }}" maxlength="190">
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label justify-between align-center" for="remarks">{{ __('Note / Remarks') }}</label>
                    <div class="form-control-wrap">
                        <input type="text" name="remarks" class="form-control" id="remarks" placeholder="{{ __('Enter remarks / note') }}" maxlength="190">
                        <input type="hidden" value="{{ $transaction->tnx }}" name="orderid">
                        <input type="hidden" value="{{ TransactionStatus::CANCELLED }}" name="status">
                    </div>
                    <div class="form-note">
                    	{{ __('The note or remarks help to reminder. Only administrator can read from transaction details.') }}
                    </div>
                </div>
            </div>
            <div class="col-12">
                @if ($type==TransactionType::REFERRAL) 
            	<p>{!! __("Please confirm that you want to CANCEL the :type.", ['type' => '<span class="fw-bold text-dark">'.strtoupper('commission').'</span>']) !!}</p>
                @else
                <p>{!! __("Please confirm that you want to CANCEL this :type request.", ['type' => '<span class="fw-bold text-dark">'.strtoupper($type).'</span>']) !!}</p>
                @endif
            </div>
        </div>
        <ul class="align-center flex-nowrap gx-2 pt-4 pb-2">
            <li>
                <button type="button" class="btn btn-primary m-tnx-update" data-confirm="yes" data-state="{{ TransactionStatus::CANCELLED }}">
                    @if ($type==TransactionType::REFERRAL) 
                    {{ __('Cancel') }}
                    @else
                    {{ __('Cancelled :Type', ['type' => $type]) }}
                    @endif
                </button>
            </li>
            <li>
                <button data-dismiss="modal" type="button" class="btn btn-trans btn-light">{{ __('Return') }}</button>
            </li>
        </ul>
        <div class="divider md stretched"></div>
        <div class="notes">
            <ul>
            	@if($type==TransactionType::DEPOSIT)
                <li class="alert-note is-plain">
                    <em class="icon ni ni-info"></em>
                    <p>{{ __("You can cancel the transaction if you've not received the payment yet.") }}</p>
                </li>
                @endif
                @if($type==TransactionType::WITHDRAW)
                <li class="alert-note is-plain">
                    <em class="icon ni ni-info"></em>
                    <p>{{ __("The withdraw amount will re-adjust into account once you confirm and cancelled.") }}</p>
                </li>
                @endif
                @if ($type!=TransactionType::REFERRAL) 
                <li class="alert-note is-plain">
                    <em class="icon ni ni-info"></em>
                    <p>{{ __("User will get email notification once cancelled the transaction.") }}</p>
                </li>
                @endif
                <li class="alert-note is-plain text-danger">
                    <em class="icon ni ni-alert"></em>
                    <p>{{ __("You can not undo this action once you confirm and cancelled.") }}</p>
                </li>
            </ul>
        </div>
    </form>
</div>