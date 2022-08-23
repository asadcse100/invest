@php
    $userAccount = $userAccount ?? compact([]);
    $formFields = data_get($method, 'config.form', []);
    $config = data_get($userAccount, 'config');
@endphp

<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
        <div class="modal-body">
            <h4 class="title">{{ blank($userAccount) ? __('Add Bank Account') : __('Update Bank Account') }}</h4>
            @if(blank($userAccount))
            <p>{{ __('Add your bank information to withdraw your funds.') }}</p>
            @else 
            <p>{{ __('Update your bank information for future withdraw.') }}</p>
            @endif
            <div class="divider sm stretched"></div>
            <form action="{{ $action }}" method="POST" class="form">
                <div class="row gy-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">{{ __('Account Type') }} <span class="text-danger">*</span></label>
                            <ul class="custom-control-group g-3 align-center">
                                <li>
                                    <div class="custom-control custom-radio">
                                        <input name="acc-type" type="radio" class="custom-control-input"
                                               id="account-personal" value="personal" @if(data_get($config, 'acc_type') == 'personal') checked @endif>
                                        <label class="custom-control-label"
                                               for="account-personal">{{ __('Personal') }}</label>
                                    </div>
                                </li>
                                <li>
                                    <div class="custom-control custom-radio">
                                        <input name="acc-type" type="radio" class="custom-control-input"
                                               id="account-business" value="business" @if(data_get($config, 'acc_type') == 'business') checked @endif>
                                        <label class="custom-control-label"
                                               for="account-business">{{ __('Business') }}</label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    @if(data_get($formFields, 'acc_name.show') == 'yes')
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="acccount-name">{{ __('Account Holder Name') }} {!! (data_get($formFields, 'acc_name.req') == 'yes') ? '<span class="text-danger">*</span>' : '' !!}</label>
                            <div class="form-control-wrap">
                                <input name="acc-name" type="text" class="form-control" id="acccount-name"
                                       placeholder="{{ __('Your Account Name') }}" value="{{ data_get($config, 'acc_name') }}">
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="account-number">{{ __('Account Number') }} <span class="text-danger">*</span></label>
                            <div class="form-control-wrap">
                                <input name="acc-number" type="text" class="form-control" id="account-number"
                                       placeholder="{{ __('eg. 39485') }}" value="{{ data_get($config, 'acc_no')}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-note text-danger mt-n1">{{ __("You must ensure that the name of the your account and bank account number should match.") }}</div>
                    </div>
                </div>
                
                @if(data_get($formFields, 'country.show') == 'yes' || data_get($formFields, 'currency.show') == 'yes')
                <div class="row gy-3 pt-1">
                    @if(data_get($formFields, 'country.show') == 'yes')
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label" for="bank-county">{{ __('Bank Location / Country') }} {!! (data_get($formFields, 'country.req') == 'yes') ? '<span class="text-danger">*</span>' : '' !!}</label>
                            <div class="form-control-wrap">
                                <select name="country" class="form-select" id="bank-county" data-search="on" data-placeholder="{{ __('Choose Country') }}">
                                    @if(filled($countries))
                                    <option></option>
                                    @foreach($countries as $code => $country)
                                    <option value="{{$country}}"{{ data_get($config, 'country') == $country ? ' selected' : '' }}>{{ config('countries')[$code] }}</option>
                                    @endforeach
                                    @else 
                                    <option selected>{{ __('No country') }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="col-sm-6">
                        @if(!blank($currencies))
                        <div class="form-group">
                            <label class="form-label" for="bank-currency">{{ __('Bank Currency') }} {!! (data_get($formFields, 'currency.req') == 'yes') ? '<span class="text-danger">*</span>' : '' !!}</label>
                            <div class="form-control-wrap">
                                <select name="currency" class="form-select" id="bank-currency" data-placeholder="{{ __('Choose Currency') }}">
                                    @if(count($currencies) > 1)
                                    @foreach($currencies as $code)
                                        <option value="{{ $code }}"{{ ($code==data_get($config, 'currency', $default) || (!in_array(data_get($config, 'currency'), $currencies) && $code==$default) ) ? ' selected' : '' }}>{{ $code }}</option>
                                    @endforeach
                                    @else
                                        <option value="{{ $default }}">{{ $default }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <div class="row gy-3 pt-1">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="bank-name">{{ __('Bank Name') }} <span class="text-danger">*</span></label>
                            <div class="form-control-wrap">
                                <input name="bank-name" type="text" value="{{data_get($config, 'bank_name')}}" class="form-control" id="bank-name" placeholder="{{ __('Your Bank Name') }}">
                            </div>
                        </div>
                    </div>

                    @if(data_get($formFields, 'bank_branch.show') == 'yes')
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="branch-name">{{ __('Branch Name') }} {!! (data_get($formFields, 'bank_branch.req') == 'yes') ? '<span class="text-danger">*</span>' : '' !!}</label>
                            <div class="form-control-wrap">
                                <input name="bank-branch" type="text" class="form-control" id="branch-name"
                                       placeholder="{{ __('Name of Branch') }}" value="{{data_get($config, 'bank_branch')}}">
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(data_get($formFields, 'bank_address.show') == 'yes')
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="branch-address">{{ __('Bank Address') }} {!! (data_get($formFields, 'bank_address.req') == 'yes') ? '<span class="text-danger">*</span>' : '' !!}</label>
                            <div class="form-control-wrap">
                                <input name="bank-address" type="text" class="form-control" id="branch-address"
                                       placeholder="{{ __('Your Bank Address') }}" value="{{data_get($config, 'bank_address')}}">
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="row gy-3 pt-1">
                    @if(data_get($formFields, 'sortcode.show') == 'yes')
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="sortcode-bic">{{ __('Sort code') }} {!! (data_get($formFields, 'sortcode.req') == 'yes') ? '<span class="text-danger">*</span>' : '' !!}</label>
                            <div class="form-control-wrap">
                                <input name="sortcode" type="text" class="form-control" id="sortcode-bic"
                                       placeholder="{{ __('Bank Sort code') }}" value="{{data_get($config, 'sortcode')}}">
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(data_get($formFields, 'routing.show') == 'yes')
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="routing-number">{{ __('Routing Number') }} {!! (data_get($formFields, 'routing.req') == 'yes') ? '<span class="text-danger">*</span>' : '' !!}</label>
                            <div class="form-control-wrap">
                                <input name="routing" type="text" class="form-control" id="routing-number"
                                       placeholder="{{ __('Routing Number') }}" value="{{data_get($config, 'routing')}}">
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(data_get($formFields, 'swift.show') == 'yes')
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="swift-bic">{{ __('Swift Code / BIC') }} {!! (data_get($formFields, 'swift.req') == 'yes') ? '<span class="text-danger">*</span>' : '' !!}</label>
                            <div class="form-control-wrap">
                                <input name="swift" type="text" class="form-control" id="swift-bic"
                                       placeholder="{{ __('Bank Swift / BIC code') }}" value="{{data_get($config, 'swift')}}">
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(data_get($formFields, 'iban.show') == 'yes')
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="iban-number">{{ __('IBAN Number') }} {!! (data_get($formFields, 'iban.req') == 'yes') ? '<span class="text-danger">*</span>' : '' !!}</label>
                            <div class="form-control-wrap">
                                <input name="iban" type="text" class="form-control" id="iban-number"
                                       placeholder="{{ __('Bank IBAN Number') }}" value="{{data_get($config, 'iban')}}">
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="row gy-3 pt-1">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="account-label">{{ __('Label of account') }} <span>{{ __('(Optional)') }}</span></label>
                            <div class="form-control-wrap">
                                <input type="text" name="wdm-label" value="{{ data_get($userAccount, 'name') }}" class="form-control" id="account-label" placeholder="eg. Personal">
                            </div>
                            <div class="form-note">
                            {{ __('You can easily identify using this.') }} {{ (blank($userAccount)) ? __('The label will auto genarate if you leave blank.') : '' }}<br>
                            {{ (isset($quickAdd) && $quickAdd) ? __('You can view or make changes the account info that saved in your Profile.') : '' }}
                        </div>
                        </div>
                    </div>
                </div>
                <div class="divider md stretched"></div>    
                <div class="row gy-3">
                    <div class="col-12">
                        @csrf
                        @if (isset($quickAdd) && $quickAdd)
                            <input type="hidden" name="quick_added" value="yes">
                        @endif
                        <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                            <li>
                                <button type="button" class="btn btn-primary" id="svu-wd-account" data-redirect="{{ (isset($quickAdd) && $quickAdd) ? 'yes' : 'no' }}">
                                    <span class="spinner-border spinner-border-sm hide" role="status" aria-hidden="true"></span>
                                    <span>{{ blank($userAccount) ? __('Add Account') : __('Update Account') }}</span>
                                </button>
                            </li>
                            @if(!blank($userAccount))
                            <li>
                                <a href="javascript:void(0)" id="delete-wd-account" class="link link-btn link-danger" data-url="{{ route('user.withdraw.account.wd-bank-transfer.delete', ['id' => the_hash(data_get($userAccount, 'id', 0))]) }}">{{ __('Delete') }}</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
