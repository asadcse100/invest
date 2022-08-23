@extends('admin.layouts.master')
@section('title', __('User Dashboard'))

@section('has-content-sidebar', 'has-content-sidebar')

@section('content-sidebar')
    @include('admin.settings.content-sidebar')
@endsection

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between gx-2">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">{{ __('User Dashboard') }}</h3>
                    <p>{{ __('Control your public pages and user dashboard.') }}</p>
                </div>
                <div class="nk-block-head-content">
                    <ul class="nk-block-tools gx-1">
                        <li class="d-lg-none">
                            <a href="#" class="btn btn-icon btn-trigger toggle" data-target="pageSidebar"><em class="icon ni ni-menu-right"></em></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="nk-block card card-bordered">
            <div class="card-inner">
                <form action="{{ route('admin.save.app.settings') }}" class="form-settings" method="POST">
                    <h5 class="title">{{ __('Manage Front-End Pages') }}</h5>
                    <p>{{ __('Customize your main home and other pages that display to your user.') }}</p>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-2 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Enable Home Page') }}</label>
                                    <span class="form-note">{{ __('Enable or disable the default home page.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input class="switch-option-value" type="hidden" name="front_page_enable" value="{{ sys_settings('front_page_enable', 'yes') }}">
                                        <input id="front-page-option" type="checkbox" class="custom-control-input switch-option" 
                                               data-switch="yes"{{ (sys_settings('front_page_enable', 'yes') == 'yes') ? ' checked=""' : ''}}>
                                        <label for="front-page-option" class="custom-control-label">{{ __('Enable') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Home Page Title') }}</label>
                                    <span class="form-note">{{ __('Set custom page tile for home page.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" name="front_page_title" value="{{ sys_settings('front_page_title') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Schemes for Home Page') }}</label>
                                    <span class="form-note">{{ __('Select few schemes that you would like to display.') }}<br>{{ __('It will show only into home page.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <select name="top_iv_plan_x0" class="form-select" data-placeholder="{{ __("Select a plan") }}" data-clear="true">
                                                    <option value=""></option>
                                                    @foreach($schemes as $plan)
                                                        <option value="{{ $plan->id }}"{{ (sys_settings('top_iv_plan_x0') == $plan->id) ? ' selected' : '' }}>{{ $plan->short.' - '.$plan->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-note">
                                                {{ __("Highlight Plan") }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <select name="top_iv_plan_x1" class="form-select" data-placeholder="{{ __("Select a plan") }}" data-clear="true">
                                                    <option value=""></option>
                                                    @foreach($schemes as $plan)
                                                        <option value="{{ $plan->id }}"{{ (sys_settings('top_iv_plan_x1') == $plan->id) ? ' selected' : '' }}>{{ $plan->short.' - '.$plan->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-note">
                                                {{ __("First Plan") }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <select name="top_iv_plan_x2" class="form-select" data-placeholder="{{ __("Select a plan") }}" data-clear="true">
                                                    <option value=""></option>
                                                    @foreach($schemes as $plan)
                                                        <option value="{{ $plan->id }}"{{ (sys_settings('top_iv_plan_x2') == $plan->id) ? ' selected' : '' }}>{{ $plan->short.' - '.$plan->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-note">
                                                {{ __("Second Plan") }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="divider"></div>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-2 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Show Additional Information') }}</label>
                                    <span class="form-note">{{ __('Show or hide additional information from home page.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input class="switch-option-value" type="hidden" name="front_page_extra" value="{{ sys_settings('front_page_extra', 'on') }}">
                                        <input id="show-additional-option" type="checkbox" class="custom-control-input switch-option" 
                                               data-switch="on"{{ (sys_settings('front_page_extra', 'on') == 'on') ? ' checked=""' : ''}}>
                                        <label for="show-additional-option" class="custom-control-label">{{ __('Enable') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Content Box :num', ['num' => '#1']) }}</label>
                                    <span class="form-note">{{ __('Set additional content to show on box.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group mb-3">
                                    <div class="form-control-wrap w-max-350px">
                                        <input type="text" class="form-control" name="extra_step1_title" value="{{ sys_settings('extra_step1_title', 'Register your free account') }}">
                                    </div>
                                    <div class="form-note mt-1">{{ __('Heading') }} <span class="pl-2">{{ __("Eg. :content", ['content' => __("Register your free account")]) }}</span></div>
                                </div>
                                <div class="form-group">
                                    <div class="form-control-wrap w-max-500px">
                                        <input type="text" class="form-control" name="extra_step1_text" value="{{ sys_settings('extra_step1_text', 'Sign up with your email and get started!') }}">
                                    </div>
                                    <div class="form-note mt-1">{{ __('Short Content') }} <span class="pl-2">{{ __("Eg. :content", ['content' => __("Sign up with your email and get started!")]) }}</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Content Box :num', ['num' => '#2']) }}</label>
                                    <span class="form-note">{{ __('Set additional content to show on box.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group mb-3">
                                    <div class="form-control-wrap w-max-350px">
                                        <input type="text" class="form-control" name="extra_step2_title" value="{{ sys_settings('extra_step2_title', 'Deposit fund and invest') }}">
                                    </div>
                                    <div class="form-note mt-1">{{ __('Heading') }} <span class="pl-2">{{ __("Eg. :content", ['content' => __("Deposit fund and invest")]) }}</span></div>
                                </div>
                                <div class="form-group">
                                    <div class="form-control-wrap w-max-500px">
                                        <input type="text" class="form-control" name="extra_step2_text" value="{{ sys_settings('extra_step2_text', 'Just top up your balance & select your desired plan.') }}">
                                    </div>
                                    <div class="form-note mt-1">{{ __('Short Content') }} <span class="pl-2">{{ __("Eg. :content", ['content' => __("Just top up your balance & select your desired plan.")]) }}</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Content Box :num', ['num' => '#3']) }}</label>
                                    <span class="form-note">{{ __('Set additional content to show on box.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group mb-3">
                                    <div class="form-control-wrap w-max-350px">
                                        <input type="text" class="form-control" name="extra_step3_title" value="{{ sys_settings('extra_step3_title', 'Payout your profits') }}">
                                    </div>
                                    <div class="form-note mt-1">{{ __('Heading') }} <span class="pl-2">{{ __("Eg. :content", ['content' => __("Payout your profits")]) }}</span></div>
                                </div>
                                <div class="form-group">
                                    <div class="form-control-wrap w-max-500px">
                                        <input type="text" class="form-control" name="extra_step3_text" value="{{ sys_settings('extra_step3_text', 'Withdraw your funds to your account once earn profit.') }}">
                                    </div>
                                    <div class="form-note mt-1">{{ __('Short Content') }} <span class="pl-2">{{ __("Eg. :content", ['content' => __("Withdraw your funds to your account once earn profit.")]) }}</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Content Box :num', ['num' => '#4']) }}</label>
                                    <span class="form-note">{{ __('Set additional content to show on box.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group mb-3">
                                    <div class="form-control-wrap w-max-350px">
                                        <input type="text" class="form-control" name="extra_step4_title" value="{{ sys_settings('extra_step4_title', 'Payment processors we accept') }}">
                                    </div>
                                    <div class="form-note mt-1">{{ __('Heading') }} <span class="pl-2">{{ __("Eg. :content", ['content' => __("Payment processors we accept")]) }}</span></div>
                                </div>
                                <div class="form-group mb-3">
                                    <div class="form-control-wrap w-max-500px">
                                        <input type="text" class="form-control" name="extra_step4_text" value="{{ sys_settings('extra_step4_text', 'We accept paypal, cryptocurrencies such as Bitcoin, Litecoin, Ethereum more.') }}">
                                    </div>
                                    <div class="form-note mt-1">{{ __('Short Content') }} <span class="pl-2">{{ __("Eg. :content", ['content' => __("We accept paypal, cryptocurrencies such as Bitcoin, Litecoin, Ethereum more.")]) }}</span></div>
                                </div>
                                <div class="form-group">
                                    <div class="form-control-wrap w-max-500px">
                                        <select name="extra_step4_icons[]" class="form-select" multiple="" data-placeholder="{{ __("Please choose icons") }}">
                                            @foreach(['paypal' => 'paypal-alt', 'bank' => 'building-fill', 'card' => 'cc-alt-fill', 'btc' => 'sign-btc', 'eth' => 'sign-eth', 'ltc' => 'sign-ltc', 'bnb' => 'sign-bnb', 'usdc' => 'sign-usdc', 'usdt' => 'sign-usdt', 'trx' => 'sign-trx'] as $name => $icon)
                                                <option value="{{ $icon }}"{{ sys_settings('extra_step4_icons') && (in_array($icon, sys_settings('extra_step4_icons'))) ? ' selected' : '' }}>{{ strtoupper($name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-note mt-1"><span class="text-secondary">{{ __('Select icons for accepted payment') }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="divider"></div>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-2 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Enable Investment Page') }}</label>
                                    <span class="form-note">{{ __('Enable or disable the investment page.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input class="switch-option-value" type="hidden" name="invest_page_enable" value="{{ sys_settings('invest_page_enable', 'yes') }}">
                                        <input id="invest-page-option" type="checkbox" class="custom-control-input switch-option" 
                                               data-switch="yes"{{ (sys_settings('invest_page_enable', 'yes') == 'yes') ? ' checked=""' : ''}}>
                                        <label for="invest-page-option" class="custom-control-label">{{ __('Enable') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Show Schemes in Investment page') }}</label>
                                    <span class="form-note">{{ __('Select whatever you would like to display.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <select name="iv_show_plans" class="form-select" id="show-plans">
                                                    <option value="default"{{ (sys_settings('iv_show_plans', 'default') == 'default') ? ' selected' : '' }}>{{ __('Default') }}</option>
                                                    <option value="featured"{{ (sys_settings('iv_show_plans') == 'featured') ? ' selected' : '' }}>{{ __('Featured Only') }}</option>
                                                </select>
                                            </div>
                                            <div class="form-note">
                                                {{ __("Display Plans") }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <div class="form-control-wrap w-max-225px">
                                                <select name="iv_plan_order" class="form-select" id="plan-ordered">
                                                    <option value="default"{{ (sys_settings('iv_plan_order') == 'default') ? ' selected' : '' }}>{{ __('Default') }}</option>
                                                    <option value="reverse"{{ (sys_settings('iv_plan_order') == 'reverse') ? ' selected' : '' }}>{{ __('Reverse') }}</option>
                                                    <option value="random"{{ (sys_settings('iv_plan_order') == 'random') ? ' selected' : '' }}>{{ __('Random') }}</option>
                                                    <option value="featured"{{ (sys_settings('iv_plan_order') == 'featured') ? ' selected' : '' }}>{{ __('Featured First') }}</option>
                                                </select>
                                            </div>
                                            <div class="form-note">
                                                {{ __("Plan Order") }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="divider"></div>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Disclaimer Context') }}</label>
                                    <span class="form-note">{{ __('Add a disclaimer message into your website.') }}<br>{{ __('It will show into footer on public pages.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <textarea class="form-control textarea-sm" name="site_disclaimer">{{ sys_settings('site_disclaimer') }}</textarea>
                                    </div>
                                    <div class="form-note">{{ __('A detailed copyright disclaimer.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3">
                            <div class="col-md-7 offset-lg-5">
                                <div class="form-group mt-2">
                                    @csrf
                                    <input type="hidden" name="form_type" value="public-pages-option">
                                    <button type="button" class="btn btn-primary submit-settings" disabled="">
                                        <span class="spinner-border spinner-border-sm hide" role="status" aria-hidden="true"></span>
                                        <span>{{ __('Update') }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="nk-block card card-bordered">
            <div class="card-inner">
                <form action="{{ route('admin.save.app.settings') }}" class="form-settings" method="POST">
                    <h5 class="title">{{ __('Navigation Setup') }}</h5>
                    <p>{{ __('Set additional navigation or menu item so user can access.') }}</p>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-2 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Main Navigation') }}</label>
                                    <span class="form-note">{{ __('Set main menu item for public pages.') }}<br>{{ __('By default home, login, register will display in menu.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap w-max-500px">
                                        <select name="main_nav[]" class="form-select" multiple="" data-placeholder="{{ __("Choose one or more pages") }}">
                                            @foreach($pages as $page)
                                                <option value="{{ $page->id }}"{{ sys_settings('main_nav') && (in_array($page->id, sys_settings('main_nav'))) ? ' selected' : '' }}>{{ $page->menu_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-note mt-1"><span class="text-secondary">{{ __('Select pages for main menu.') }}</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Footer Navigation') }}</label>
                                    <span class="form-note">{{ __('Set footer navigation for public pages and user panel.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap w-max-500px">
                                        <select name="footer_menu[]" class="form-select" multiple="" data-placeholder="{{ __("Choose one or more pages") }}">
                                            @foreach($pages as $page)
                                                <option value="{{ $page->id }}"{{ sys_settings('footer_menu') && (in_array($page->id, sys_settings('footer_menu'))) ? ' selected' : '' }}>{{ $page->menu_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-note mt-1"><span class="text-secondary">{{ __('Select pages forfooter menu.') }}</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Additional Navigation') }}</label>
                                    <span class="form-note">{{ __('Set additional menu item on sidebar.') }}<br>{{ __('This will display in user panel under main navigation.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group mb-3">
                                    <div class="form-control-wrap w-max-350px">
                                        <input type="text" class="form-control" name="main_menu_heading" value="{{ sys_settings('main_menu_heading') }}">
                                    </div>
                                    <div class="form-note mt-1"><span class="text-secondary">{{ __('Set additional heading') }}</span></div>
                                </div>
                                <div class="form-group">
                                    <div class="form-control-wrap w-max-500px">
                                        <select name="main_menu[]" class="form-select" multiple="" data-placeholder="{{ __("Choose one or more pages") }}">
                                            @foreach($pages as $page)
                                                <option value="{{ $page->id }}"{{ sys_settings('main_menu') && (in_array($page->id, sys_settings('main_menu'))) ? ' selected' : '' }}>{{ $page->menu_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-note mt-1"><span class="text-secondary">{{ __('Select pages for navigation.') }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <h5 class="title">{{ __('Page Setup') }}</h5>
                    <p>{{ __('These pages need to be set so that system know where to display to user.') }}</p>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Terms and Conditions Page') }}</label>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap w-max-250px">
                                        <select name="page_terms" class="form-select" data-placeholder="{{ __("Select a page") }}" data-clear="true">
                                            <option value=""></option>
                                            @foreach($pages as $page)
                                                <option value="{{ $page->id }}"{{ (sys_settings('page_terms') == $page->id) ? ' selected' : '' }}>{{ $page->menu_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Privacy Page') }}</label>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap w-max-250px">
                                        <select name="page_privacy" class="form-select" data-placeholder="{{ __("Select a page") }}" data-clear="true">
                                            <option value=""></option>
                                            @foreach($pages as $page)
                                                <option value="{{ $page->id }}"{{ (sys_settings('page_privacy') == $page->id) ? ' selected' : '' }}>{{ $page->menu_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="row align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Deposits Fees Page') }}</label>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap w-max-250px">
                                        <select name="page_fee_deposit" class="form-select" data-placeholder="{{ __("Select a page") }}" data-clear="true">
                                            <option value=""></option>
                                            @foreach($pages as $page)
                                                <option value="{{ $page->id }}"{{ (sys_settings('page_fee_deposit') == $page->id) ? ' selected' : '' }}>{{ $page->menu_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Withdraws Fees Page') }}</label>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap w-max-250px">
                                        <select name="page_fee_withdraw" class="form-select" data-placeholder="{{ __("Select a page") }}" data-clear="true">
                                            <option value=""></option>
                                            @foreach($pages as $page)
                                                <option value="{{ $page->id }}"{{ (sys_settings('page_fee_withdraw') == $page->id) ? ' selected' : '' }}>{{ $page->menu_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="row align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Contact / Support Page') }}</label>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap w-max-250px">
                                        <select name="page_contact" class="form-select" data-placeholder="{{ __("Select a page") }}" data-clear="true">
                                            <option value=""></option>
                                            @foreach($pages as $page)
                                                <option value="{{ $page->id }}"{{ (sys_settings('page_contact') == $page->id) ? ' selected' : '' }}>{{ $page->menu_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Enable Form on Support page') }}</label>
                                    <span class="form-note">{{ __('Display a contact form to send direct email.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input class="switch-option-value" type="hidden" name="page_contact_form" value="{{ sys_settings('page_contact_form') ?? 'on' }}">
                                        <input id="contact-form-option" type="checkbox" class="custom-control-input switch-option" 
                                               data-switch="on"{{ (sys_settings('page_contact_form', 'on') == 'on') ? ' checked=""' : ''}}>
                                        <label for="contact-form-option" class="custom-control-label">{{ __('Enable') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3">
                            <div class="col-md-7 offset-lg-5">
                                <div class="form-group mt-2">
                                    @csrf
                                    <input type="hidden" name="form_type" value="userpanel-setting">
                                    <button type="button" class="btn btn-primary submit-settings" disabled="">
                                        <span class="spinner-border spinner-border-sm hide" role="status" aria-hidden="true"></span>
                                        <span>{{ __('Update') }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="nk-block card card-bordered">
            <div class="card-inner">
                <form action="{{ route('admin.save.app.settings') }}" class="form-settings" method="POST">
                    <h5 class="title">{{ __('Misc Options') }}</h5>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Alert for Adding Withdraw Account') }}</label>
                                    <span class="form-note w-max-300px">{{ __('Show an alert if user have not added account info for withdraw or payment receiving.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input class="switch-option-value" type="hidden" name="alert_wd_account" value="{{ sys_settings('alert_wd_account') ?? 'on' }}">
                                        <input id="wd-account-option" type="checkbox" class="custom-control-input switch-option" 
                                               data-switch="on"{{ (sys_settings('alert_wd_account', 'on') == 'on') ? ' checked=""' : ''}}>
                                        <label for="wd-account-option" class="custom-control-label">{{ __('Enable') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Alert for Missing Basic Information') }}</label>
                                    <span class="form-note w-max-300px">{{ __('Show an alert if user have not update basic information like phone, dob & country etc.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input class="switch-option-value" type="hidden" name="alert_profile_basic" value="{{ sys_settings('alert_profile_basic') ?? 'on' }}">
                                        <input id="profile-basic-option" type="checkbox" class="custom-control-input switch-option" 
                                               data-switch="on"{{ (sys_settings('alert_profile_basic', 'on') == 'on') ? ' checked=""' : ''}}>
                                        <label for="profile-basic-option" class="custom-control-label">{{ __('Enable') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="divider"></div>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Global Notice or News') }}</label>
                                    <span class="form-note">{{ __('Display news or global notice into dashboard.') }}<br>{{ __('It will show into header on the user panel.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input class="switch-option-value" type="hidden" name="header_notice_show" value="{{ sys_settings('header_notice_show') ?? 'no' }}">
                                        <input id="gh-notice" type="checkbox" class="custom-control-input switch-option" 
                                               data-switch="yes"{{ (sys_settings('header_notice_show', 'no') == 'yes') ? ' checked=""' : ''}}>
                                        <label for="gh-notice" class="custom-control-label">{{ __('Show') }}</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" name="header_notice_title" value="{{ sys_settings('header_notice_title') }}">
                                    </div>
                                    <div class="form-note">{{ __('Notice / News Heading (approx 8-10 words)') }}</div>
                                </div>
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" name="header_notice_text" value="{{ sys_settings('header_notice_text') }}">
                                    </div>
                                    <div class="form-note">
                                        <span>{{ __('Notice / News Shorten Text (approx 10-15 words)') }}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" name="header_notice_link" value="{{ sys_settings('header_notice_link') }}">
                                    </div>
                                    <div class="form-note">{{ __('Notice / News External Link (include http://)') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-2 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Set Expiry Date for Notice') }}</label>
                                    <span class="form-note">{{ __('Notice will display into user dashboard until the date.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <div class="form-text-hint">
                                            <em class="icon ni ni-calender-date"></em>
                                        </div>
                                        <input class="form-control date-picker" data-date-start-date="-1d" name="header_notice_date" type="text" value="{{ sys_settings('header_notice_date') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="divider"></div>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Live Currency Rates Ticker') }}</label>
                                    <span class="form-note">{{ __('Display a live rates ticker into header in user panel.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input class="switch-option-value" type="hidden" name="rates_ticker_display" value="{{ sys_settings('rates_ticker_display') ?? 'no' }}">
                                        <input id="rates-ticker" type="checkbox" class="custom-control-input switch-option" 
                                               data-switch="yes"{{ (sys_settings('rates_ticker_display', 'no') == 'yes') ? ' checked=""' : ''}}>
                                        <label for="rates-ticker" class="custom-control-label">{{ __('Show') }}</label>
                                    </div>
                                    <span class="form-note text-info mt-1">{{ __('Ticker will only display if global news is hide or expired.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label" for="rates-ticker-from">{{ __('Base Currency in Ticker') }}</label>
                                    <span class="form-note">{{ __('Choose the main currency to display in ticker.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap w-max-250px">
                                        <select class="form-select" name="rates_ticker_from" id="rates-ticker-base">
                                            <option value="base"{{ (sys_settings('rates_ticker_from') == 'base') ? ' selected' : '' }}>{{ __("Base Currency") }}</option>
                                            <option value="alter"{{ (sys_settings('rates_ticker_from') == 'alter') ? ' selected' : '' }}>{{ __("Secondary Currency") }}</option>
                                            @foreach($currencies as $code => $name)
                                                <option value="{{ $code }}"{{ (sys_settings('rates_ticker_from') == $code) ? ' selected' : '' }}>{{ $name.' ('.$code.')' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label" for="rates-ticker-fx">{{ __('Display Currencies in Ticker') }}</label>
                                    <span class="form-note">{{ __('Choose rates of currencies to display in ticker.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="form-control-wrap w-max-250px">
                                        <select class="form-select" name="rates_ticker_fx" id="rates-ticker-fx">
                                            <option value="all"{{ (sys_settings('rates_ticker_fx') == 'all') ? ' selected' : '' }}>{{ __("Available Currencies") }}</option>
                                            <option value="custom"{{ (sys_settings('rates_ticker_fx') == 'custom') ? ' selected' : '' }}>{{ __("Custom Selected Currencies") }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 align-start">
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Select Currency in Ticker') }}</label>
                                    <span class="form-note">
                                        {{ __('Currency rate will be show against base currency.') }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <ul class="custom-control-group g-2 align-center flex-wrap li-col3x">
                                    @foreach($currencies as $code => $name)
                                    <li>
                                        <div class="custom-control custom-control-sm custom-checkbox">
                                            <input type="checkbox" id="rates-ticker-cur-{{ strtolower($code) }}" class="custom-control-input" name="rates_ticker_currencies[{{ $code }}]"{{ (in_array($code, array_keys(sys_settings('rates_ticker_currencies', [])))) ? ' checked' : '' }}>
                                            <label class="custom-control-label" for="rates-ticker-cur-{{ strtolower($code) }}">{{ $name.' ('.$code.')' }}</label>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="divider"></div>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3 align-start">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Support / Help Card') }}</label>
                                    <span class="form-note">{{ __('Display extra support card into dashboard.') }}<br>{{ __('It will show into bottom of the user dashboard.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input class="switch-option-value" type="hidden" name="support_card_show" value="{{ sys_settings('support_card_show') ?? 'no' }}">
                                        <input id="shc-notice" type="checkbox" class="custom-control-input switch-option" 
                                               data-switch="yes"{{ (sys_settings('support_card_show', 'no') == 'yes') ? ' checked=""' : ''}}>
                                        <label for="shc-notice" class="custom-control-label">{{ __('Show') }}</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" name="support_card_title" value="{{ sys_settings('support_card_title') }}">
                                    </div>
                                    <div class="form-note">{{ __('Main Heading') }}</div>
                                </div>
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" name="support_card_text" value="{{ sys_settings('support_card_text') }}">
                                    </div>
                                    <div class="form-note">
                                        <span>{{ __('Sub Text for Support') }}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" name="support_card_link" value="{{ sys_settings('support_card_link') }}">
                                    </div>
                                    <div class="form-note">{{ __('External Link (include http://)') }} {{ __('If blank, it will link to contact page.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-sets gy-3 wide-md">
                        <div class="row g-3">
                            <div class="col-md-7 offset-lg-5">
                                <div class="form-group mt-2">
                                    @csrf
                                    <input type="hidden" name="form_type" value="other-options">
                                    <button type="button" class="btn btn-primary submit-settings" disabled="">
                                        <span class="spinner-border spinner-border-sm hide" role="status" aria-hidden="true"></span>
                                        <span>{{ __('Update') }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
