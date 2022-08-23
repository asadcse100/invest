@php 

use \App\Enums\TransactionType;
use \App\Enums\TransactionStatus;

$amount = $transaction->amount;
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
    <p>{!! __("User (:name) request to withdraw :amount via :Method. Please send the payment to user account and complete the withdraw.", ['method' => '<span class="fw-bold text-dark">'.$transaction->method_name.'</span>', 'amount' => '<span class="fw-bold text-dark text-nowrap">'.$user_amount.'</span>', 'name' => '<span class="fw-bold text-dark">'.the_uid($transaction->customer->id).'</span>' ]) !!}</p>

    @if (data_get($transaction, 'tnx_fees')) 
    <p class="small text-dark">
        {!! __("Total withdraw :amount including fees :fee.", ['amount' => '<strong>'.money(data_get($transaction, 'tnx_total'), $tnx_currency, ['dp' => 'calc']).'</strong>', 'fee' => '<strong>'.money(data_get($transaction, 'tnx_fees'), $tnx_currency, ['dp' => 'calc']).'</strong>']) !!}
    </p>
    @endif

    <div class="divider md stretched"></div>

    <form action="{{ route('admin.transaction.update', ['action' => 'approve', 'uid' => the_hash($transaction->id)]) }}" data-action="update">
        <div class="row gy-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label justify-between align-center" for="reference">{{ __('Reference / Hash') }}</label>
                    <div class="form-control-wrap">
                        <input type="text" name="reference" class="form-control" id="reference" placeholder="{{ __('Reference or Hash') }}" maxlength="190">
                    </div>
                    <div class="form-note">
                        {{ __('The reference will display to user.') }}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label justify-between align-center" for="payfrom">{{ __('Paid From') }}</label>
                    <div class="form-control-wrap">
                        <input type="text" name="payfrom" class="form-control" id="payfrom" value="{{ $transaction->pay_from }}" placeholder="{{ __('Sending account name or id') }}" maxlength="190">
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
                <p>{!! __("Confirm that you've paid and want to COMPLETED this :type request.", ['type' => '<span class="fw-bold text-dark ucap">'. __(ucfirst($type)) .'</span>']) !!}</p>
            </div>
        </div>
        <ul class="align-center flex-nowrap gx-2 pt-4 pb-2">
            <li>
                <button type="button" class="btn btn-primary m-tnx-update" data-confirm="yes" data-state="{{ TransactionStatus::COMPLETED }}">{{ __('Completed Withdraw') }}</button>
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
                <p>{{ __("User will get email notification once completed the withdraw.") }}</p>
            </li>
            <li class="alert-note is-plain text-danger">
                <em class="icon ni ni-alert"></em>
                <p>{{ __("You can not undo this action once you confirm and completed.") }}</p>
            </li>
        </ul>
    </div>
</div>