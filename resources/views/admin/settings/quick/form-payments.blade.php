<form action="{{ route('admin.quick-setup.save') }}" class="form-settings" method="POST">
    <h5 class="title mb-3">{{ __('Payment Settings') }}</h5>
    <div class="form-sets">
        <div class="row gy-2">
            <div class="col-12">
                <div class="form-group">
                    <label class="form-label">{{ __('Minimum Deposit') }}</label>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <div class="form-text-hint"><span>{{ base_currency() }}</span></div>
                                    <input type="number" class="form-control" name="deposit_fiat_minimum" value="{{ sys_settings('deposit_fiat_minimum', '1') }}" min="0">
                                </div>
                                <div class="form-note"><strong class="text-base">{{ __('Fiat Currency') }}</strong></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <div class="form-text-hint"><span>{{ base_currency() }}</span></div>
                                    <input type="number" class="form-control" name="deposit_crypto_minimum" value="{{ sys_settings('deposit_crypto_minimum', '0') }}" min="0">
                                </div>
                                <div class="form-note"><strong class="text-base">{{ __('Crypto Currency') }}</strong></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label class="form-label">{{ __('Maximum Deposit') }}</label>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <div class="form-text-hint"><span>{{ base_currency() }}</span></div>
                                    <input type="number" class="form-control" name="deposit_fiat_maximum" value="{{ sys_settings('deposit_fiat_maximum', '1') }}" min="0">
                                </div>
                                <div class="form-note"><strong class="text-base">{{ __('Fiat Currency') }}</strong></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <div class="form-text-hint"><span>{{ base_currency() }}</span></div>
                                    <input type="number" class="form-control" name="deposit_crypto_maximum" value="{{ sys_settings('deposit_crypto_maximum', '0') }}" min="0">
                                </div>
                                <div class="form-note"><strong class="text-base">{{ __('Crypto Currency') }}</strong></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label class="form-label">{{ __('Minimum Withdraw') }}</label>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <div class="form-text-hint"><span>{{ base_currency() }}</span></div>
                                    <input type="number" class="form-control" name="withdraw_fiat_minimum" value="{{ sys_settings('withdraw_fiat_minimum', '1') }}" min="0">
                                </div>
                                <div class="form-note"><strong class="text-base">{{ __('Fiat Currency') }}</strong></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <div class="form-text-hint"><span>{{ base_currency() }}</span></div>
                                    <input type="number" class="form-control" name="withdraw_crypto_minimum" value="{{ sys_settings('withdraw_crypto_minimum', '0') }}" min="0">
                                </div>
                                <div class="form-note"><strong class="text-base">{{ __('Crypto Currency') }}</strong></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label class="form-label">{{ __('Maximum Withdraw') }}</label>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <div class="form-text-hint"><span>{{ base_currency() }}</span></div>
                                    <input type="number" class="form-control" name="withdraw_fiat_maximum" value="{{ sys_settings('withdraw_fiat_maximum', '1') }}" min="0">
                                </div>
                                <div class="form-note"><strong class="text-base">{{ __('Fiat Currency') }}</strong></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <div class="form-text-hint"><span>{{ base_currency() }}</span></div>
                                    <input type="number" class="form-control" name="withdraw_crypto_maximum" value="{{ sys_settings('withdraw_crypto_maximum', '0') }}" min="0">
                                </div>
                                <div class="form-note"><strong class="text-base">{{ __('Crypto Currency') }}</strong></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-sets mt-4">
        <div class="d-flex justify-between align-center">
            <div class="action">
                @csrf
                <input type="hidden" name="form_next" value="misc">
                <input type="hidden" name="form_type" value="payment-setting">
                <input type="submit" class="btn btn-primary submit-settings" value="{{ __('Update & Next') }}">
            </div>
            <div class="action">
                <a class="link link-primary" href="{{ route('admin.quick-setup', ['step' => 'misc']) }}">{{ __("Skip & Next") }}</a>
            </div>
        </div>
    </div>
</form>