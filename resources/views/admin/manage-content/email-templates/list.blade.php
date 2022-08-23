@extends('admin.layouts.master')
@section('title', __('Manage Email Template'))

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">{{ __('Email Templates') }}</h3>
                    <p>{{ __('Manage your email template that send to customers.') }}</p>
                </div>
                <div class="nk-block-head-content">
                    <ul class="nk-block-tools g-3">
                        <li>
                            <a href="{{ route('admin.settings.email') }}" class="btn btn-primary d-none d-sm-inline-flex">
                                <em class="icon ni ni-mail-fill"></em><span>{{ __('Email Setting') }}</span>
                            </a>
                            <a href="{{ route('admin.settings.email') }}" class="btn btn-icon btn-primary d-inline-flex d-sm-none">
                                <em class="icon ni ni-mail-fill"></em>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="nk-block">
            @if(filled($templates))
                <div class="card card-bordered card-stretch">
                    <div class="nk-tb-list nk-tb-emaillist is-compact">
                        <div class="nk-tb-item nk-tb-head">
                            <div class="nk-tb-col"><span>{{ __('Email Template') }}</span></div>
                            <div class="nk-tb-col tb-col-sm"><span>{{ __('Group') }}</span></div>
                            <div class="nk-tb-col tb-col-mb"><span>{{ __('Recipient(s)') }}</span></div>
                            <div class="nk-tb-col tb-col-mb"><span>{{ __('Notify') }}</span></div>
                            <div class="nk-tb-col nk-tb-col-tools">&nbsp;</div>
                        </div>
                        @foreach($templates as $template)
                            <div class="nk-tb-item">
                                <div class="nk-tb-col">
                                    <a href="{{ route('admin.manage.email.template.edit', data_get($template, 'slug')) }}"><span
                                            class="tb-lead">{{ data_get($template, 'name') }}</span></a>
                                </div>
                                <div class="nk-tb-col tb-col-sm">
                                    <span class="tb-lead-sub">{{ ucwords(str_replace(['_', '-'], ' ', data_get($template, 'group'))) }}</span>
                                </div>
                                <div class="nk-tb-col tb-col-mb">
                                    <span class="text-sub"
                                    @if(data_get($template, 'recipient')== $iRecipient->ADMIN)
                                        @if( !blank(data_get($template, 'addresses.recipient')) && (data_get($template, 'addresses.recipient')=='alternet'||data_get($template, 'addresses.recipient')=='custom') )
                                             data-toggle="tooltip" title="{{ __('Send to :recipient email', ['recipient' => data_get($template, 'addresses.recipient') ]) }}"
                                        @else
                                             data-toggle="tooltip" title="{{ __('Send to default email') }}"
                                        @endif
                                    @endif
                                    >{{ ucfirst(data_get($template, 'recipient')) }}</span>

                                    @if( data_get($template, 'recipient')== $iRecipient->ADMIN && !blank(data_get($template, 'addresses.emails')) )
                                        <span data-toggle="tooltip" title="{{ __('Send a copy as CC') }}">
                                            <em class="icon ni ni-info fs-12px"></em>
                                        </span>
                                    @endif

                                    @if( data_get($template, 'recipient')== $iRecipient->ADMIN && data_get($template, 'addresses.recipient')=='custom' && blank(data_get($template, 'addresses.custom')) )
                                        <span data-toggle="tooltip" title="{{ __('Custom email  not defined') }}">
                                            <em class="icon ni ni-alert-fill fs-12px text-warning"></em>
                                        </span>
                                    @endif
                                </div>
                                <div class="nk-tb-col tb-col-mb">
                                    <span class="tb-status{{ data_get($template, 'status') == $iStatus->ACTIVE ? ' text-primary': ' text-danger'}}">{{ data_get($template, 'status') == $iStatus->ACTIVE ? __('Yes') : __('No')}}</span>
                                </div>
                                <div class="nk-tb-col nk-tb-col-tools">
                                    <ul class="nk-tb-actions gx-1">
                                        <li class="nk-tb-action-hidden">
                                            <a href="{{ route('admin.manage.email.template.edit', data_get($template, 'slug')) }}"
                                               class="btn btn-trigger btn-icon" data-toggle="tooltip"
                                               data-placement="top" title="{{ __('Edit') }}">
                                                <em class="icon ni ni-edit-fill"></em>
                                            </a>
                                        </li>
                                        <li>
                                            <div class="drodown">
                                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger"
                                                   data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <ul class="link-list-opt no-bdr">
                                                        <li>
                                                            <a href="{{ route('admin.manage.email.template.edit', data_get($template, 'slug')) }}">
                                                                <em class="icon ni ni-edit"></em><span>{{ __('Edit Template') }}</span>
                                                            </a>
                                                        </li>
                                                        <li class="divider"></li>
                                                        <li>
                                                            <a href="javascript:void(0)" class="send-test-mail" data-slug="{{ data_get($template, 'slug') }}">
                                                                <em class="icon ni ni-mail"></em><span>{{ __('Test Email') }}</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if(filled($templates) && $templates->hasPages())
                    <div class="card-inner border-top pt-3 pb-4">
                        {{ $templates->appends(request()->all())->links('misc.pagination') }}
                    </div>
                    @endif
                </div>
            @else
                <div class="alert alert-primary">
                    <div class="alert-cta flex-wrap flex-md-nowrap">
                        <div class="alert-text">
                            <p>{{ __('No Email Templates Found') }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript">
    const mail_sent_url = "{{ route('admin.manage.email.template.test') }}";
</script>
@endpush