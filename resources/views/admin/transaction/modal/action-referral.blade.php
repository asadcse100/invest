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
	<h5 class="title mb-3">{!! __('Commission ID# :orderid', ['orderid' => '<span class="text-primary">'.the_tnx($transaction->tnx).'</span>' ]) !!}</h5>
	<p class="caption-text">{!! __("The commission amount of :amount add into :account.", ['account' => '<span class="fw-bold text-dark">'.$transaction->customer->username.'</span>', 'amount' => '<span class="fw-bold text-dark text-nowrap">'.$user_amount.'</span>' ]) !!}</p>
</div>
<div class="nk-block">
    <form action="{{ route('admin.transaction.update', ['action' => 'approve', 'uid' => the_hash($transaction->id)]) }}" data-action="update">
        <div class="row gy-3">
        	<div class="col-sm-5">
        		<div class="form-group">
					<label class="form-label">{{ __('Commission Amount') }}</label>
                    <div class="form-control-wrap">
                    	<div class="form-text-hint"><span class="overline-title">{{ $tnx_currency }}</span></div>
                        <input type="text" value="{{ to_num($tnx_amount) }}" class="form-control" readonly="">
                    </div>
                    <div class="form-note">
                    	{{ __('The amount will receive user.') }}
                    </div>
                </div>
        	</div>
            <div class="col-sm-7">
        		<div class="form-group">
					<label class="form-label">{{ __('Commission For') }}</label>
                    <div class="form-control-wrap">
                        <input type="text" value="{{ data_get($transaction, 'meta.referral.action') }} / {{ data_get($transaction, 'meta.referral.type') }}{{ !in_array(data_get($transaction, 'meta.referral.level'), ['lv1', 'lv0']) ? str_replace('lv', ' / level', data_get($transaction, 'meta.referral.level')) : '' }}" class="form-control" readonly="">
                    </div>
                    <div class="form-note">
                    	{{ __('Purpose of the commission.') }}
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
            	<p>{!! __("Please confirm that you want to PAY this :type commission.", ['type' => '<span class="fw-bold text-dark">'.strtoupper($type).'</span>']) !!}</p>
            </div>
        </div>
        <ul class="align-center flex-nowrap gx-2 pt-4 pb-2">
            <li>
                <button type="button" class="btn btn-primary m-tnx-update" data-confirm="yes" data-state="{{ TransactionStatus::COMPLETED }}">{{ __('Pay Commission') }}</button>
            </li>
            <li>
                <button data-dismiss="modal" type="button" class="btn btn-trans btn-light">{{ __('Return') }}</button>
            </li>
        </ul>
        <div class="divider md stretched"></div>
        <div class="notes">
            <ul>
                <li class="alert-note is-plain">
                    <em class="icon ni ni-info"></em>
                    <p>{{ __("The amount will adjust into user account once you confirmed.") }}</p>
                </li>
                <li class="alert-note is-plain text-danger">
                    <em class="icon ni ni-alert"></em>
                    <p>{{ __("You can not undo this action once you Pay Commission and procced.") }}</p>
                </li>
            </ul>
        </div>
    </form>
</div>