@php
use \App\Enums\TransactionType;
use \App\Enums\TransactionStatus;

$type = $transaction->type;

@endphp
<div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
    	<a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
        <div class="modal-body modal-body-md">
        @if( $method=='cancel' || $method=='reject' )

        	@include('admin.transaction.modal.action-cancel', ['transaction' => $transaction, 'method' => $method, 'type' => $type])
	  
        @elseif($method=='approve' && $type==TransactionType::DEPOSIT)

        	@include('admin.transaction.modal.action-deposit', ['transaction' => $transaction, 'method' => $method, 'type' => $type])

        @elseif($method=='approve' && $type==TransactionType::REFERRAL)

            @include('admin.transaction.modal.action-referral', ['transaction' => $transaction, 'method' => $method, 'type' => $type])

        @elseif($type==TransactionType::WITHDRAW && (in_array($method, ['confirm', 'approve'])))

            @if($method=='confirm')

            	@include('admin.transaction.modal.action-confirm', ['transaction' => $transaction, 'method' => $method, 'type' => $type])
            
            @else 

            	@include('admin.transaction.modal.action-withdraw', ['transaction' => $transaction, 'method' => $method, 'type' => $type])
            
	        @endif

        @elseif($method=='approve' && $type==TransactionType::TRANSFER && module_exist('FundTransfer', 'mod') && view()->exists('FundTransfer::admin.action-transfer'))

            @include('FundTransfer::admin.action-transfer', ['transaction' => $transaction, 'method' => $method, 'type' => $type])

	    @else 

            <div class="nk-modal modal-body-sm text-center">
                <em class="nk-modal-icon icon icon-circle icon-circle-xxl ni ni-alert bg-warning"></em>
                <h4 class="nk-modal-title title">{{ __("Invalid Action") }}</h4>
                <div class="nk-modal-text">
                    <p class="caption-text">{{ __("Sorry, we unable to procced your request. The action may not valid to apply or already applied on this transaction.") }}</p>
                    <p class="sub-text-sm">{{ __("Please reload the page and try once again.") }}</p>
                </div>
                <div class="nk-modal-action-lg">
                    <ul class="btn-group gx-4">
                        <li><a href="#" data-dismiss="modal" class="btn btn-lg btn-mw btn-light">{{ __("Return") }}</a></li>
                    </ul>
                </div>
            </div>

        @endif
        </div>
    </div>
</div>