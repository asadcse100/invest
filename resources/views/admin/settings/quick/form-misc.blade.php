<form action="{{ route('admin.quick-setup.save') }}" class="form-settings" method="POST">
    <h5 class="title mb-3">{{ __('Optional Settings') }}</h5>
    <div class="form-sets">
        <div class="row g-3">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">{{ __('Time Zone') }}</label>
                    <div class="form-control-wrap">
                        <select name="time_zone" class="form-select">
                            @foreach(config('investorm.timezones') as $key => $item)
                                <option value="{{ $key }}"{{ (sys_settings('time_zone') == $key) ? ' selected' : '' }}>{{ __($item) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <span class="form-note">{{ __('Set timezone on application.') }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">{{ __('Date Format') }}</label>
                    <div class="form-control-wrap">
                        <select name="date_format" class="form-select">
                            @foreach(config('investorm.date_formats') as $key => $item)
                                <option value="{{ $key }}"{{ (sys_settings('date_format') == $key) ? ' selected' : '' }}>{{ __($item) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <span class="form-note">{{ __('Set date format to display date.') }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">{{ __('Time Format') }}</label>
                    <div class="form-control-wrap">
                        <select name="time_format" class="form-select">
                            @foreach(config('investorm.time_formats') as $key => $item)
                                <option value="{{ $key }}"{{ (sys_settings('time_format') == $key) ? ' selected' : '' }}>{{ __($item) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <span class="form-note">{{ __('Set time format to display time.') }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="form-sets mt-3">
        <label class="form-label">{{ __('Maximum Decimal') }} <span class="small"> - {{ __('Application') }}</span></label>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <div class="form-control-wrap">
                        <input type="number" class="form-control" min="2" max="6" name="decimal_fiat_calc" value="{{ sys_settings('decimal_fiat_calc', '2') }}">
                    </div>
                    <div class="form-note"><strong>{{ __('Fiat Currency') }}</strong> {{ __('(2 to 6 accepted)') }}</div>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <div class="form-control-wrap">
                        <input type="number" class="form-control" min="4" max="12" name="decimal_crypto_calc" value="{{ sys_settings('decimal_crypto_calc', '6') }}">
                    </div>
                    <div class="form-note"><strong>{{ __('Crypto Currency') }}</strong> {{ __('(4 to 12 accepted)') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-sets mt-3">
        <label class="form-label">{{ __('Decimal Display') }} <span class="small"> - {{ __('Optional / Alternate') }}</span></label>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <div class="form-control-wrap">
                        <input type="number" class="form-control" min="1" max="4" name="decimal_fiat_display" value="{{ sys_settings('decimal_fiat_display', '2') }}">
                    </div>
                    <div class="form-note"><strong>{{ __('Fiat Currency') }}</strong> {{ __('(1 to 4 accepted)') }}</div>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <div class="form-control-wrap">
                        <input type="number" class="form-control" min="4" max="8" name="decimal_crypto_display" value="{{ sys_settings('decimal_crypto_display', '4') }}">
                    </div>
                    <div class="form-note"><strong>{{ __('Crypto Currency') }}</strong> {{ __('(4 to 8 accepted)') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-sets mt-4">
        <div class="d-flex justify-between align-center">
            <div class="action">
                @csrf
                <input type="hidden" name="form_next" value="complete">
                <input type="hidden" name="form_type" value="misc-setting">
                <input type="submit" class="btn btn-primary submit-settings" value="{{ __('Update & Complete') }}">
            </div>
        </div>
    </div>
</form>