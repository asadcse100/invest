@extends('frontend.layouts.master')

@section('title', __(gss('front_page_title', "Welcome")))
@section('desc', gss('seo_description_home', gss('seo_description', '')))
@section('keyword', gss('seo_keyword_home', gss('seo_keyword', '')))

@section('content')
@if(!empty($schemes))
<section class="section {{ (gss('ui_page_skin', 'dark') == 'dark') ? 'bg-grad-stripe-botttom' : 'bg-lighter pt-5' }} py-1">
    <div class="container wide-lg">
        <div class="row justify-content-center g-gs">

            <div class="col-lg-4 order-lg-2">
                <div class="pricing card card-shadow h-100 is-dark round-lg text-center">
                    <div class="card-inner card-inner-lg h-100 d-flex flex-column">
                        <h5 class="pricing-title">{{ data_get($schemes['highlight'], 'name') }}</h5>
                        <span class="fs-20px">{{ data_get($schemes['highlight'], 'total_return') }}% {{ __("ROI") }}</span>
                        <div class="pricing-parcent">
                            <h3 class="percent">{{ data_get($schemes['highlight'], 'rate_text') }}</h3>
                            <h5 class="text">{{ __(data_get($schemes['highlight'], 'calc_period')) }}</h5>
                        </div>
                        <ul class="pricing-feature">
                            <li>
                                <span>{{ __("Investment Period") }}</span>
                                <span>{{ __(data_get($schemes['highlight'], 'term_text_alter')) }}</span>
                            </li>
                            <li>
                                <span>{{ __("Investments") }}</span>
                                @if(data_get($schemes['highlight'], 'is_fixed'))
                                <span>{{ money(data_get($schemes['highlight'], 'amount'), base_currency()) }}</span>
                                @else
                                <span>{{ money(data_get($schemes['highlight'], 'amount'),  base_currency()) }} - {{ data_get($schemes['highlight'], 'maximum') ? money(data_get($schemes['highlight'], 'maximum'),  base_currency()) : __("Unlimited") }}</span>
                                @endif
                            </li>
                            @if(sys_settings('iv_plan_terms_show') == 'yes')
                            <li>
                                <span class="label">{{ __('Term Duration') }}</span>
                                <span class="data">{{ data_get($schemes['highlight'], 'term_text_alter') }}</span>
                            </li>
                            @endif
                            @if(sys_settings('iv_plan_payout_show') == 'yes')
                            <li>
                                <span class="label">{{ __('Payout Term') }}</span>
                                <span class="data">{{ data_get($schemes['highlight'], 'payout') == 'after_matured' ? __("After matured") : __("Term basis") }}</span>
                            </li>
                            @endif
                            @if(sys_settings('iv_plan_capital_show') == 'yes')
                            <li>
                                <span>{{ __("Capital Return") }}</span>
                                <span>{{ (data_get($schemes['highlight'], 'capital') == 1) ? __("End of Term") : __("Each Term") }}</span>
                            </li>
                            @endif
                        </ul>
                        <div class="pricing-action mt-auto">
                            @if (!auth()->check())
                            <a class="btn btn-primary btn-lg btn-block" href="{{ route('auth.register.form') }}">{{ __("Make a deposit") }}</a>
                            @else
                            <a class="btn btn-primary btn-lg btn-block" href="{{ route('user.investment.invest', data_get($schemes['highlight'], 'uid_code')) }}">{{ __("Invest Now") }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="pricing card card-shadow h-100 round-lg text-center">
                    <div class="card-inner card-inner-lg h-100 d-flex flex-column">
                        <h5 class="pricing-title">{{ data_get($schemes['one'], 'name') }}</h5>
                        <span class="fs-20px">{{ data_get($schemes['one'], 'total_return') }}% {{ __("ROI") }}</span>
                        <div class="pricing-parcent">
                            <h3 class="percent">{{ data_get($schemes['one'], 'rate_text') }}</h3>
                            <h5 class="text">{{ __(data_get($schemes['one'], 'calc_period')) }}</h5>
                        </div>
                        <ul class="pricing-feature">
                            <li>
                                <span>{{ __("Investment Period") }}</span>
                                <span>{{ __(data_get($schemes['one'], 'term_text_alter')) }}</span>
                            </li>
                            <li>
                                <span>{{ __("Investments") }}</span>
                                @if(data_get($schemes['one'], 'is_fixed'))
                                <span>{{ money(data_get($schemes['one'], 'amount'), base_currency()) }}</span>
                                @else
                                <span>{{ money(data_get($schemes['one'], 'amount'),  base_currency()) }} - {{ data_get($schemes['one'], 'maximum') ? money(data_get($schemes['one'], 'maximum'),  base_currency()) : __("Unlimited") }}</span>
                                @endif
                            </li>
                            @if(sys_settings('iv_plan_terms_show') == 'yes')
                            <li>
                                <span class="label">{{ __('Term Duration') }}</span>
                                <span class="data">{{ data_get($schemes['one'], 'term_text_alter') }}</span>
                            </li>
                            @endif
                            @if(sys_settings('iv_plan_payout_show') == 'yes')
                            <li>
                                <span class="label">{{ __('Payout Term') }}</span>
                                <span class="data">{{ data_get($schemes['one'], 'payout') == 'after_matured' ? __("After matured") : __("Term basis") }}</span>
                            </li>
                            @endif
                            @if(sys_settings('iv_plan_capital_show') == 'yes')
                            <li>
                                <span>{{ __("Capital Return") }}</span>
                                <span>{{ (data_get($schemes['one'], 'capital') == 1) ? __("End of Term") : __("Each Term") }}</span>
                            </li>
                            @endif
                        </ul>
                        <div class="pricing-action mt-auto">
                            @if (!auth()->check())
                            <a class="btn btn-light btn-lg btn-block" href="{{ route('auth.register.form') }}">{{ __("Make a deposit") }}</a>
                            @else
                            <a class="btn btn-light btn-lg btn-block" href="{{ route('user.investment.invest', data_get($schemes['one'], 'uid_code')) }}">{{ __("Invest Now") }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="pricing card card-shadow h-100 round-lg text-center">
                    <div class="card-inner card-inner-lg h-100 d-flex flex-column">
                        <h5 class="pricing-title">{{ data_get($schemes['two'], 'name') }}</h5>
                        <span class="fs-20px">{{ data_get($schemes['two'], 'total_return') }}% {{ __("ROI") }}</span>
                        <div class="pricing-parcent">
                            <h3 class="percent">{{ data_get($schemes['two'], 'rate_text') }}</h3>
                            <h5 class="text">{{ __(data_get($schemes['two'], 'calc_period')) }}</h5>
                        </div>
                        <ul class="pricing-feature">
                            <li>
                                <span>{{ __("Investment Period") }}</span>
                                <span>{{ __(data_get($schemes['two'], 'term_text_alter')) }}</span>
                            </li>
                            <li>
                                <span>{{ __("Investments") }}</span>
                                @if(data_get($schemes['two'], 'is_fixed'))
                                <span>{{ money(data_get($schemes['two'], 'amount'), base_currency()) }}</span>
                                @else
                                <span>{{ money(data_get($schemes['two'], 'amount'),  base_currency()) }} - {{ data_get($schemes['two'], 'maximum') ? money(data_get($schemes['two'], 'maximum'),  base_currency()) : __("Unlimited") }}</span>
                                @endif
                            </li>
                            @if(sys_settings('iv_plan_terms_show') == 'yes')
                            <li>
                                <span class="label">{{ __('Term Duration') }}</span>
                                <span class="data">{{ data_get($schemes['two'], 'term_text_alter') }}</span>
                            </li>
                            @endif
                            @if(sys_settings('iv_plan_payout_show') == 'yes')
                            <li>
                                <span class="label">{{ __('Payout Term') }}</span>
                                <span class="data">{{ data_get($schemes['two'], 'payout') == 'after_matured' ? __("After matured") : __("Term basis") }}</span>
                            </li>
                            @endif
                            @if(sys_settings('iv_plan_capital_show') == 'yes')
                            <li>
                                <span>{{ __("Capital Return") }}</span>
                                <span>{{ (data_get($schemes['two'], 'capital') == 1) ? __("End of Term") : __("Each Term") }}</span>
                            </li>
                            @endif
                        </ul>
                        <div class="pricing-action mt-auto">
                            @if (!auth()->check())
                            <a class="btn btn-light btn-lg btn-block" href="{{ route('auth.register.form') }}">{{ __("Make a deposit") }}</a>
                            @else
                            <a class="btn btn-light btn-lg btn-block" href="{{ route('user.investment.invest', data_get($schemes['two'], 'uid_code')) }}">{{ __("Invest Now") }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- .row --}}
    </div>{{-- .container --}}
</section>
@endif

@if(gss('front_page_extra', 'on')=='on' && (!auth()->check() || (auth()->check() && auth()->user()->role=='user')))
<section class="section">
    <div class="container wide-lg">
        <div class="row g-gs">

            <div class="col-lg-8">
                <div class="row g-gs">
                    @if(!auth()->check())
                    <div class="col-sm-6 col-md-4">
                        <div class="card card-shadow text-center h-100">
                            <div class="card-inner">
                                <div class="card-image">
                                    <img src="{{ asset('images/icon-a.png') }}" alt="">
                                </div>
                                <div class="card-text mt-4">
                                    <h6 class="title fs-14px">{{ (gss('extra_step1_title')) ? __(gss('extra_step1_title')) : __("Register your free account") }}</h6>
                                    <p>{{ (gss('extra_step1_text')) ? __(gss('extra_step1_text')) : __("Sign up with your email and get started!") }}</p>
                                </div>
                            </div>
                            <div class="card-inner py-2 border-top mt-auto">
                                <a class="link" href="{{ route('auth.register.form') }}">{{ __("Create an account") }}</a>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="{{ (auth()->check()) ? 'col-md-6' : 'col-md-4 col-sm-6' }}">
                        <div class="card card-shadow text-center h-100">
                            <div class="card-inner">
                                <div class="card-image">
                                    <img src="{{ asset('/images/icon-b.png') }}" alt="">
                                </div>
                                <div class="card-text mt-4">
                                    <h6 class="title fs-14px">{{ (gss('extra_step2_title')) ? __(gss('extra_step2_title')) : __("Deposit fund and invest") }}</h6>
                                    <p>{{ (gss('extra_step2_text')) ? __(gss('extra_step2_text')) : __("Just top up your balance & select your desired plan.") }}</p>
                                </div>
                            </div>
                            <div class="card-inner py-2 border-top mt-auto">
                                @if (!auth()->check())
                                <a class="link" href="{{ route('auth.register.form') }}">{{ __("Make a deposit") }}</a>
                                @else
                                <a class="link" href="{{ route('deposit') }}">{{ __("Make a deposit") }}</a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="{{ (auth()->check()) ? 'col-md-6' : 'col-md-4' }}">
                        <div class="card card-shadow text-center h-100">
                            <div class="card-inner">
                                <div class="card-image">
                                    <img src="{{ asset('/images/icon-c.png') }}" alt="">
                                </div>
                                <div class="card-text mt-4">
                                    <h6 class="title fs-14px">{{ (gss('extra_step3_title')) ? __(gss('extra_step3_title')) : __("Payout your profits") }}</h6>
                                    <p>{{ (gss('extra_step3_text')) ? __(gss('extra_step3_text')) : __("Withdraw your funds to your account once earn profit.") }}</p>
                                </div>
                            </div>
                            <div class="card-inner py-2 border-top mt-auto">
                                @if (!auth()->check())
                                <a class="link" href="{{ route('auth.register.form') }}">{{ __("Withdraw profits") }}</a>
                                @else
                                <a class="link" href="{{ route('withdraw') }}">{{ __("Withdraw profits") }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-shadow text-center h-100">
                    <div class="card-inner card-inner-lg my-auto">
                        <div class="card-text my-lg-n2">
                            <h6 class="title fs-14px">{{ (gss('extra_step4_title')) ? __(gss('extra_step4_title')) : __("Payment processors we accept") }}</h6>
                            <p>{{ (gss('extra_step4_text')) ? __(gss('extra_step4_text')) : __("We accept paypal, cryptocurrencies such as Bitcoin, Litecoin, Ethereum more.") }}</p>
                            @php
                            $accepted_icons = gss('extra_step4_icons', ['paypal-alt', 'sign-btc', 'sign-eth', 'sign-ltc']);
                            @endphp

                            @if (!empty($accepted_icons) && is_array($accepted_icons))
                            <ul class="icon-list icon-bordered icon-rounded mb-3">
                                @foreach ($accepted_icons as $icon)
                                <li><em class="icon ni ni-{{ $icon }}"></em></li>
                                @endforeach
                            </ul>
                            @endif

                            <div class="payment-action">
                                @if (!auth()->check())
                                <a href="{{ route('auth.register.form') }}" class="btn btn-lg btn-primary btn-block"><span class="text-wrap">{{ __("Join now") }} {{ __("and") }} {{ __("make deposit") }}</span></a>
                                @else
                                <a class="btn btn-lg btn-primary btn-block" href="{{ route('deposit') }}">{{ __("Make a deposit") }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@else
<div class="gap gap-lg"></div>
@endif

@endsection
