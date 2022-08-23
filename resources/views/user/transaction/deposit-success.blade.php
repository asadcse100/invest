@php
    use App\Enums\TransactionStatus;
@endphp

<div class="nk-pps-apps">
    <div class="nk-pps-result">
        <em class="icon icon-circle icon-circle-xxl ni ni-check bg-success"></em>
        <h3 class="title">{{ __('Deposit Succeeded!') }}</h3>
        <div class="nk-pps-text">
            @if(is_crypto($transaction->tnx_currency) || data_get($transaction, 'status') != TransactionStatus::COMPLETED)
                <p class="caption-text">{{ __('The amount will be credited into your account upon admin confirmation.') }}</p>
            @else
                <p class="caption-text">{{ __('The amount has been successfully credited into your account.') }}</p>
            @endif

            @if(filled($transaction))
                <p class="sub-text">{{ __('Transaction ID') }} {{ the_tnx($transaction->tnx) }}</p>
            @endif
        </div>
        <div class="nk-pps-action">
            <ul class="btn-group-vertical align-center gy-3">
                <li><a href="{{ route('deposit') }}" class="btn btn-lg btn-mw btn-primary">{{ __('Deposit More') }}</a></li>
                <li><a href="{{ route('dashboard') }}" class="link link-primary">{{ __('Go back to Dashboard') }}</a></li>
            </ul>
        </div>
    </div>
</div>
