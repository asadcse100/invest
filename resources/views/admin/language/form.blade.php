@php

use App\Enums\Boolean;  

@endphp

<div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
        <a href="javascript:void(0)" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
        <div class="modal-body modal-body-md">
            <h4 class="title">{{ ($update) ? __('Edit Language') : __('Add Language') }}</h4>
            <p>{{ ($update) ? __('Update the language in your application.') : __('Add a new language in your application.') }}</p>
            <form action="{{ ($update) ? route('admin.system.langs.update', the_hash($lang->id)) : route('admin.system.langs.add') }}" class="form-settings is-alter" id="language-form" method="POST" autocomplete="off">
                <div class="form-sets gy-3 wide-md">
                    <div class="row gy-2 gx-gs">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label class="form-label">{{ __('Language Name') }} <span class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" name="name" value="{{ ($update) ? $lang->name : "" }}" 
                                    placeholder="{{ __('Eg. English') }}"{{ ($update) ? ' readonly=""' : '' }} required="">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="form-label">{{ __('Lanauge Code') }} <span class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" name="code" value="{{ ($update) ? $lang->code : "" }}" 
                                    placeholder="{{ __('Eg. en') }}"{{ ($update) ? ' readonly=""' : '' }} required="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-2 gx-gs">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label class="form-label">{{ __('Lanauge Label') }} <span class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" name="label" value="{{ ($update) ? $lang->label : "" }}" placeholder="{{ __('Eg. English') }}" required="">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="form-label">{{ __('Short Name') }}</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" name="short" value="{{ ($update) ? $lang->short  : "" }}" placeholder="{{ __('Eg. ENG') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-2 gx-gs">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label class="form-label">{{ __('Text Direction') }} <span class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <select name="text_direction" class="form-select">
                                        <option value="ltr" {{ ($update) ? ($lang->rtl == Boolean::NO ? ' selected' : '') : '' }}>{{ __('LTR') }}</option>
                                        <option value="rtl" {{ ($update) ? ($lang->rtl == Boolean::YES ? ' selected' : '') : '' }}>{{ __('RTL') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="divider md stretched"></div>
                <div class="form-sets gy-3 wide-md">
                    <div class="row">
                        <div class="col-12">
                            <div class="align-center flex-nowrap g-3">
                                @if($update)
                                <div class="col">
                                    <div class="custom-control custom-switch">
                                        <input name="status" id="status" type="checkbox" class="custom-control-input"{{ (data_get($lang, 'status', Boolean::YES) == Boolean::YES) ? ' checked=""' : ''}}>
                                        <label for="status" class="custom-control-label">{{ __('Active') }}</label>
                                    </div>
                                </div>
                                @endif
                                <div class="col">
                                    <ul class="align-center justify-content-end flex-nowrap gx-4">
                                        <li class="order-last">
                                            @csrf
                                            <button type="button" class="btn btn-primary qma-lang-save">
                                                <span class="spinner-border spinner-border-sm hide" role="status" aria-hidden="true"></span>
                                                <span>{{  ($update) ? __("Update") : __('Add New') }}</span>
                                            </button>
                                        </li>
                                        <li class="d-none d-sm-inline">
                                            <a href="#" data-dismiss="modal" class="link link-danger">{{ __('Cancel') }}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>