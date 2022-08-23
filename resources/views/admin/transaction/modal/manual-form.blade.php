@php

use \App\Enums\TransactionType;

$title = ($todo=='any') ? __('Transaction') : $todo;
$fund = ($todo=='any') ? __('Transaction') : $todo;
$form_url = isset($ivTnx) ?  route('admin.investment.manual.save', $todo) : route('admin.transaction.manual.save', $todo); 

@endphp

<div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
        <a href="javascript:void(0)" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
        <div class="modal-body modal-body-md">
            <h4 class="title nk-modal-title mb-3">{{ __('Manually Add :Type', ['type' => $title]) }}</h4>
            <form action="{{$form_url }}" method="POST" data-confirm="addnew" class="form-validate is-alter">
                <div class="row gy-2">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label" for="tnx-amount">{{ __('Amount') }} <span class="text-danger">*</span></label>
                            <div class="form-control-wrap">
                                <div class="form-text-hint"><span class="overline-title">{{ base_currency() }}</span></div>
                                <input type="number" class="form-control" id="tnx-amount" name="amount" required="">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label" for="tnx-account">{{__('Add to Account')}} <span class="text-danger">*</span></label>
                            <div class="form-control-wrap">
                                <select name="account" id="tnx-account" class="form-control form-select" data-placeholder="{{ __("Select an account") }}" data-search="on" required="">
                                    <option value="">{{ __("Select an account") }}</option>
                                    @if(!blank($users))
                                        @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ __(':uid (:name)', ['uid' => the_uid($user->id), 'name' => $user->name, 'email' => str_protect($user->email)]) }}</option>
                                        @endforeach
                                    @else 
                                        <option>{{ __('No user found.') }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label" for="tnx-type">{{__('Transaction Type')}} <span class="text-danger">*</span></label>
                            <div class="form-control-wrap">
                                <select name="tnxtype" id="tnx-type" class="form-control form-select" data-placeholder="{{ __("Select transaction type") }}" required="">
                                    @if($todo=='any'|| empty($todo))
                                        <option value="">{{ __("Select transaction type") }}</option>
                                        @foreach ($types as $item)
                                            <option value="{{ $item['name'] }}">{{ $item['label'] }}</option>
                                        @endforeach
                                    @else 
                                        <option value="{{ $types[$todo]['name'] }}">{{ $types[$todo]['label'] }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label" for="tnx-method">{{ __('Method') }} <span class="text-danger">*</span></label>
                            <div class="form-control-wrap">
                                <select name="tnxmethod" id="tnx-method" class="form-control form-select" required="">
                                @foreach ($methods as $pm)
                                    <option value="{{ $pm['name'] }}">{{ $pm['label'] }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="tnx-description">{{ __('Description') }}</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control " id="tnx-description" name="description">
                            </div>
                            <div class="form-note">
                                {{ __("Reason for the transaction. The description will display to user.") }}<br>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="tnx-note">{{ __('Note / Remarks') }}</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control " id="tnx-note" name="remarks">
                            </div>
                            <div class="form-note">{{ __('The note or remarks help to reminder. Only administrator can read from transaction details.') }}</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2 py-1">
                            <li>
                                <button type="button" class="btn btn-primary m-tnx-create">{{ __('Add :Type', ['type' => $title]) }}</button>
                            </li>
                            <li>
                                <a href="javascript:void(0)" data-dismiss="modal" class="link link-light">{{ __('Cancel') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="divider md stretched"></div>
                <div class="notes">
                    <ul>
                    @if(isset($ivTnx))
                        @if($todo=='any' || $todo=='loss' || $todo=='penalty')
                        <li class="alert-note is-plain">
                            <em class="icon ni ni-info"></em>
                            <p><strong class="text-dark">{{ __("Add Loss") }} / {{ __("Add Penalty") }}</strong>: {{ __("Amount will be deduct/debit from invest account balance.") }}</p>
                        </li>
                        @endif
                        @if($todo=='any' || $todo=='profit')
                        <li class="alert-note is-plain">
                            <em class="icon ni ni-info"></em>
                            <p><strong class="text-dark">{{ __("Add Profit") }}</strong>: {{ __("Amount will add/credit into invest account balance.") }}</p>
                        </li>
                        @endif
                    @else
                        @if($todo=='any' || $todo=='charge')
                        <li class="alert-note is-plain">
                            <em class="icon ni ni-info"></em>
                            <p><strong class="text-dark">{{ __("Add Charge") }}</strong>: {{ __("Amount will be deduct/debit from account balance.") }}</p>
                        </li>
                        @endif
                        <li class="alert-note is-plain">
                            <em class="icon ni ni-info"></em>
                            <p><strong class="text-dark">{{ __("Add Bonus/Deposit") }}</strong>: {{ __("Amount will add/credit into account balance.") }}</p>
                        </li>
                        <li class="alert-note is-plain">
                            <em class="icon ni ni-alert"></em>
                            <p>{{ __("Leaving blank description will add 'Debited Balance' or 'Credited Balance' based on type.") }}</p>
                        </li>
                    @endif
                        <li class="alert-note is-plain text-danger">
                            <em class="icon ni ni-alert"></em>
                            <p>{{ __("You can not undo this action once you confirmed to add.") }}</p>
                        </li>
                    </ul>
                </div>
            </form>
        </div>
    </div>
</div>