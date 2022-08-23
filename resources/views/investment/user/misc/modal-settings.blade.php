<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
    	<a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
        <div class="modal-body modal-body-md">
			<div class="nk-modal-title">
				<h4 class="title mb-3">{!! __('Balance Transfer Setting') !!}</h4>
				<p class="caption-text">{!! __("You can transfer your available balance manually or automatically transfer your balance to your main account.") !!}</p>
			</div>
			<div class="nk-block">
			    <form action="{{ route('user.investment.settings.save') }}" method="POST">
			        <div class="row gy-2">
                        <div class="col-sm-7">
                            <div class="form-group">
                                <label class="form-label" for="auto-transfer">{{ __('Enable Automatic Transfer') }}</label>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="auto_transfer" class="custom-control-input switch-option "{{ ($setting === true) ? ' checked' : '' }} id="auto-transfer">
                                    <label class="custom-control-label" for="auto-transfer"></label>
                                </div>
                            </div>
                        </div>
						<div class="col-sm-12">
			        		<div class="form-group">
								<label class="form-label" for="min-transfer">{{ __('Threshold Minimum Amount') }}</label>
			                    <div class="form-control-wrap">
			                    	<div class="form-text-hint"><span class="overline-title">{{ base_currency() }}</span></div>
			                        <input type="text" id="min-transfer" name="min_transfer" value="{{ data_get($metas, 'setting_min_transfer') ?? '' }}" class="form-control">
			                    </div>
			                    <div class="form-note">
			                    	{{ __("Default minimum: :amount", ['amount' => money(gss("iv_min_transfer"), base_currency())]) }}
			                    </div>
			                </div>
			        	</div>
			        </div>
			        <ul class="align-center flex-nowrap gx-2 pt-4 pb-2">
			            <li>
			                <button type="button" class="btn btn-primary iv-settings-save">{{ __('Update') }}</button>
			            </li>
			        </ul>
					<div class="divider md stretched"></div>
			        <div class="notes mb-n2">
			            <ul>
			                <li class="alert-note is-plain text-primary">
			                    <em class="icon ni ni-info"></em>
			                    <p>{{ __("Your balance will be transferred automatically if available balance covers the threshold minimum amount.") }}</p>
			                </li>
			            </ul>
			        </div>
			    </form>
			</div>
        </div>
    </div>
</div>

<script>
	$(document).on('click', '.iv-settings-save', function() {
		let $self = $(this), $form = $self.parents('form'), url = $form.attr('action'), data = $form.serialize();

		if(url) {
			NioApp.Form.toPost(url, data, {
				btn: $self,
				after: function(res) {
					setTimeout(function() { $self.parents('.modal').modal('hide'); }, 800);
				}
			});
		}
	});
</script>