<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
    	<a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
        <div class="modal-body modal-body-md">
        	@if($balance)
			<div class="nk-modal-title">
				<h4 class="title mb-3">{!! __('Transfer Balance') !!}</h4>
				<p class="caption-text">{!! __("You can transfer investment account balance to main account.") !!}</p>
			</div>
			<div class="nk-block">
			    <form action="{{ route('user.investment.payout.proceed') }}" method="POST">
			        <div class="row gy-3">
			        	<div class="col-sm-12">
			        		<div class="form-group">
								<label class="form-label">{{ __('Available Funds') }}</label>
			                    <div class="form-control-wrap">
			                    	<div class="form-text-hint"><span class="overline-title">{{ base_currency() }}</span></div>
			                        <input type="text" value="{{ $balance }}" class="form-control form-control-lg" readonly="">
			                    </div>
			                    <div class="form-note">
			                    	{{ __("Pending or locked amount is not included in available funds.") }}
			                    </div>
			                </div>
			        	</div>
			            <div class="col-sm-12">
			        		<div class="form-group">
								<label class="form-label">{{ __('Amount to Transfer') }}</label>
			                    <div class="form-control-wrap">
			                    	<div class="form-text-hint"><span class="overline-title">{{ base_currency() }}</span></div>
			                        <input type="text" name="amount" value="{{ $balance }}" class="form-control form-control-lg">
			                    </div>
			                    <div class="form-note">
			                    	{{ __("The amount you want to transfer into main account.") }}
			                    </div>
			                </div>
			        	</div>
			        </div>
			        <ul class="align-center flex-nowrap gx-2 pt-4 pb-2">
			            <li>
			                <button type="button" class="btn btn-lg btn-primary iv-payout-proceed" data-confirm="transfer">{{ __('Transfer Now') }}</button>
			            </li>
			            <li>
			                <button data-dismiss="modal" type="button" class="btn btn-trans btn-light">{{ __('Cancel') }}</button>
			            </li>
			        </ul>
			        <div class="divider md stretched"></div>
			        <div class="notes mb-n2">
			            <ul>
			                <li class="alert-note is-plain text-primary">
			                    <em class="icon ni ni-info"></em>
			                    <p>{{ __("The amount will immediately transfer into your main account so you can withdraw funds or re-investment.") }}</p>
			                </li>
			            </ul>
			        </div>
			    </form>
			</div>
			@else 
			<div class="nk-modal modal-body-sm text-center">
                <em class="nk-modal-icon icon icon-circle icon-circle-xxl ni ni-alert bg-warning"></em>
                <h4 class="nk-modal-title title">{{ __("Oops, insufficient balance") }}</h4>
                <div class="nk-modal-text">
                    <p class="caption-text">{{ __("You do not have enough funds in your investment account. Try again, once funds available.") }}</p>
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