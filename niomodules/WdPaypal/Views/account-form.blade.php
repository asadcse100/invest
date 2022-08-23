@php
    $userAccount = $userAccount ?? compact([]);
@endphp
<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
        <div class="modal-body modal-body-md">
            <h4 class="title">{{ blank($userAccount) ? __('Add Paypal Account') : __('Update Paypal Account') }}</h4>
            @if(blank($userAccount))
            <p>{{ __('Add your paypal email address to withdraw your funds.') }}</p>
            @else 
            <p>{{ __('Update your existing paypal account for future withdraw.') }}</p>
            @endif
            <div class="divider stretched"></div>
            <form action="{{$actionUrl}}" method="POST" class="form">
                <div class="row gy-3">
                    <div class="col-md-12">
                        <div class="row gx-1">
                            <div class="col-8 col-md-9">
                                <div class="form-group">
                                    <label class="form-label" for="paypal-email">{{ __('Paypal email address') }} <span class="text-danger">*</span></label>
                                    <div class="form-control-wrap">
                                        <input type="email" name="wdm-email" value="{{ data_get($userAccount, 'config.email') }}" class="form-control form-control-lg" id="paypal-email" placeholder="{{ __('Enter email address') }}">                                    
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 col-md-3">
                                @if(!blank($currencies))
                                <div class="form-group">
                                    <label class="form-label" for="paypal-currency">{{ __('Currency') }}</label>
                                    <div class="form-control-wrap">
                                        <select name="wdm-currency" class="form-select" id="paypal-currency" data-ui="lg">
                                            @if(count($currencies) > 1)
                                            @foreach($currencies as $currency)
                                                <option value="{{ $currency }}"{{ ($currency==data_get($userAccount, 'config.currency', $default)) ? ' selected' : '' }}>{{ $currency }}</option>
                                            @endforeach
                                            @else
                                                <option value="{{ $default }}">{{ $default }}</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="col-12">
                                <div class="form-note mt-2">{{ __('You will receive payment on this account in selected currency.') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label" for="account-label">{{ __('Label of account') }} <span>{{ __('(Optional)') }}</span></label>
                            <div class="form-control-wrap">
                                <input type="text" name="wdm-label" value="{{ data_get($userAccount, 'name') }}" class="form-control form-control-lg" id="account-label" placeholder="eg. Personal">
                            </div>
                            <div class="form-note">
                                {{ __('You can easily identify using this.') }} {{ (blank($userAccount)) ? __('The label will auto genarate if you leave blank.') : '' }}<br>
                                {{ (isset($quickAdd) && $quickAdd) ? __('You can view or make changes the account info that saved in your Profile.') : '' }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="divider stretched"></div>
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
                                <a href="javascript:void(0)" id="delete-wd-account" class="link link-btn link-danger" data-url="{{route('user.withdraw.account.wd-paypal.delete', ['id' => the_hash(data_get($userAccount, 'id', 0))])}}">{{ __('Delete') }}</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
