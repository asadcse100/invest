@extends('admin.layouts.master')
@section('title', __('Edit Template'))

@section('has-content-sidebar', 'has-content-sidebar')

@section('content-sidebar')
    @include('admin.manage-content.email-templates.content-sidebar', compact('templateList', 'templateDetails'))
@endsection

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">{{ __('Email') }} / <span class="text-primary small">{{ data_get($templateDetails, 'name') }}</span></h3>
                    <div class="nk-block-des text-soft small">
                        <ul class="list-inline">
                            <li>{{ __('Email send to:') }} <span class="text-base">{{ ucfirst(data_get($templateDetails, 'recipient')) }}</span></li>
                            <li>{{ __('Template Relate:') }} <span class="text-base">{{ ucwords(str_replace(['_', '-'], ' ', data_get($templateDetails, 'group'))) }}</span></li>
                        </ul>
                    </div>
                </div>
                <div class="nk-block-head-content">
                    <ul class="nk-block-tools gx-2">
                        <li>
                            <a href="{{ route('admin.manage.email.template') }}" class="btn btn-outline-light bg-white d-none d-sm-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                            <a href="{{ route('admin.manage.email.template') }}" class="btn btn-icon btn-trigger d-inline-flex d-sm-none"><em class="icon ni ni-arrow-left"></em></a>
                        </li>
                        <li class="d-lg-none">
                            <a href="#" class="btn btn-icon btn-trigger toggle" data-target="pageSidebar"><em class="icon ni ni-menu-right"></em></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="nk-block">
            <div class="card card-bordered card-stretch">
                <div class="card-inner">
                    <form action="{{ route('admin.manage.email.template.save') }}" class="form-settings">
                        <input type="hidden" name="slug" value="{{ data_get($templateDetails, 'slug') }}">
                        <div class="form-sets">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ __('Email Subject') }}</label>
                                        <div class="form-control-wrap">
                                            <input name="subject" value="{{ data_get($templateDetails, 'subject') }}" type="text" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ __('Greeting') }}</label>
                                        <div class="form-control-wrap">
                                            <input name="greeting" value="{{ data_get($templateDetails, 'greeting') }}" type="text" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="description" class="form-label">{{ __('Email Content') }}</label>
                                        <div class="form-control-wrap">
                                            <textarea name="content" name="message" class="form-control textarea-lg">{{ data_get($templateDetails, 'content') }}</textarea>
                                        </div>
                                        <div class="form-note fs-12px font-italic mt-2">
                                            <p class="text-soft">{{ __('You can use these shortcut:') }} {{ '[[site_name]], [[site_email]], [[user_name]], [[user_email]], [[user_id]]'.(!blank(data_get($templateDetails, 'shortcut')) ? ', '. data_get($templateDetails, 'shortcut') : '') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="divider my-4"></div>
                        <div class="form-sets">
                            <div class="row g-3">
                                @if(data_get($templateDetails, 'recipient') == \App\Enums\EmailRecipientType::ADMIN)
                                <div class="col-12 mb-2">
                                    <div class="row gy-3">
                                        <div class="col-md-5 col-xxl-3">
                                            <div class="form-group">
                                                <label class="form-label">{{ __('Email Recipient') }}</label>
                                                <div class="form-control-wrap">
                                                    <select name="addresses[recipient]" class="form-select">
                                                        <option value="default" @if(data_get($templateDetails, 'addresses.recipient') == 'default') selected @endif>{{ __('Default Email') }}</option>
                                                        <option value="alternet" @if(data_get($templateDetails, 'addresses.recipient') == 'alternet') selected @endif>{{ __('Alternet Email') }}</option>
                                                        <option value="custom" @if(data_get($templateDetails, 'addresses.recipient') == 'custom') selected @endif>{{ __('Custom Email') }}</option>
                                                        @foreach($adminUsers as $admin)
                                                        <option value="{{ data_get($admin, 'id') }}" @if(data_get($templateDetails, 'addresses.recipient') == data_get($admin, 'id')) selected @endif>{{ data_get($admin, 'name').' ('.data_get($admin, 'email').')' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-note">{!! __('Define address from :link', ['link' => '<a href="'.route('admin.settings.email').'">'.__('Email Cofiguration').'</a>']) !!}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-7 col-xxl-5">
                                            <div class="form-group">
                                                <label class="form-label">{{ __('Custom Email') }}</label>
                                                <div class="form-control-wrap">
                                                    <input name="addresses[custom]" value="{{ data_get($templateDetails, 'addresses.custom') }}" type="text" class="form-control">
                                                </div>
                                                <div class="form-note">{{ __('If email recipient selected as custom email.') }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xxl-8">
                                            <div class="form-group">
                                                <label class="form-label">{{ __('Email Addresses') }} <span>({{ __('Send as CC') }})</span></label>
                                                <div class="form-control-wrap">
                                                    <input name="addresses[emails]" value="{{ data_get($templateDetails, 'addresses.emails') }}" type="text" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="col-12">
                                    <div class="custom-control-group">
                                        <div class="custom-control custom-switch">
                                            <input class="switch-option-value" type="hidden"
                                                   name="params[regards]"
                                                   value="{{ data_get($templateDetails, 'params.regards') }}">
                                            <input id="email-footer" type="checkbox"
                                                   class="custom-control-input switch-option"
                                                   data-switch="on"{{ (data_get($templateDetails, 'params.regards') == 'on') ? ' checked=""' : ''}}>
                                            <label class="custom-control-label" for="email-footer">{{ __('Enable global email footer') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="custom-control-group">
                                        <div class="custom-control custom-switch">
                                            <input class="switch-option-value" type="hidden"
                                                   name="status"
                                                   value="{{ data_get($templateDetails, 'status') }}">
                                            <input id="enable-email" type="checkbox"
                                                   class="custom-control-input switch-option"
                                                   data-switch="active"{{ (data_get($templateDetails, 'status') == 'active') ? ' checked=""' : ''}}>
                                            <label class="custom-control-label" for="enable-email">{{ __('Enable this email notification') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-sets mt-gs">
                            <div class="form-group">
                                <button type="button" class="btn btn-primary submit-settings" disabled="">
                                            <span class="spinner-border spinner-border-sm hide" role="status"
                                                  aria-hidden="true"></span>
                                    <span>{{ __('Update') }}</span>
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="divider"></div>
                    <form action="{{ route('admin.manage.email.template.test') }}" class="form-settings wide-sm">
                        <label class="form-label" for="email-to-test">{{ __('Test Email Address') }}</label>
                        <div class="row mt-4 gy-2">
                            <div class="col-sm-8 col-md-6">
                                <div class="form-control-wrap">
                                    <input type="text" name="send_to" class="form-control">
                                    <input type="hidden" name="slug" value="{{ data_get($templateDetails, 'slug') }}">
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-6">
                                <div class="form-control-wrap">
                                    <button type="button" class="btn btn-primary send-test-mail" disabled="">
                                        <span class="spinner-border spinner-border-sm hide" role="status" aria-hidden="true"></span>
                                        <span>{{ __('Send Test Email') }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
