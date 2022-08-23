@php 

$supported_currency = array_keys(sys_settings('supported_currency', '{}')); 

@endphp

<form action="{{ route('admin.quick-setup.save') }}" class="form-settings" method="POST">
    <h5 class="title mb-3">{{ __('System Currencies') }}</h5>
    <div class="form-sets">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" for="system-currency">{{ __('Base Currency') }}</label>
                    <div class="form-control-wrap">
                        <select class="form-select" name="base_currency" id="system-currency">
                            @foreach($currencies as $code => $name)
                                <option value="{{ $code }}"{{ ($code==sys_settings('base_currency')) ? ' selected' : '' }}>{{ $name.' ('.$code.')' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-note">{{ __('System Default Currency') }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" for="alter-currency">{{ __('Secondary Currency') }}</label>
                    <div class="form-control-wrap w-max-250px">
                        <select class="form-select" name="alter_currency" id="alter-currency">
                            @foreach($currencies as $code => $name)
                                <option value="{{ $code }}"{{ ($code==sys_settings('alter_currency')) ? ' selected' : '' }}>{{ $name.' ('.$code.')' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-note">{{ __('Alternet Display Currency') }}</div>
                </div>
            </div>
            <div class="col-12">
                <div class="form-note text-danger">
                    <strong>{{ __("Caution:") }}</strong> {{ __('The base currency is important as amount calculation depend on it. Remember problem will occurred, if you change it later after transaction made.') }}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label" for="currency-supported">{{ __('Supported Currency') }}</label>
                    <div class="form-control-wrap">
                        <select name="supported_currency[]" class="form-select" multiple="" data-placeholder="Choose your desired currencies">
                            @foreach($currencies as $code => $name)
                                <option value="{{ $code }}"{{ (in_array($code, $supported_currency)) ? ' selected' : '' }}>{{ $code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <span class="form-note">
                        {{ __('Select one or more currencies that you want to enable.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="form-sets mt-4">
        <div class="d-flex justify-between align-center">
            <div class="action">
                @csrf
                <input type="hidden" name="form_next" value="payments">
                <input type="hidden" name="form_type" value="currencies-setting">
                <input type="submit" class="btn btn-primary submit-settings" value="{{ __('Update & Next') }}">
            </div>
            <div class="action">
                <a class="link link-primary" href="{{ route('admin.quick-setup', ['step' => 'payments']) }}">{{ __("Skip & Next") }}</a>
            </div>
        </div>
    </div>
</form>