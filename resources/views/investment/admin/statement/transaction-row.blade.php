@php

use App\Enums\LedgerTnxType;
use App\Enums\TransactionCalcType;

$type = data_get($transaction, 'type');
$meta = json_decode(data_get($transaction, 'meta'));
$badge_class = 'badge-dark';

if(LedgerTnxType::INVEST==$type) {
    $badge_class = 'badge-info';
}
if(LedgerTnxType::CAPITAL==$type) {
    $badge_class = 'badge-success';
}
if(LedgerTnxType::PROFIT==$type) {
    $badge_class = 'badge-success';
}
if(LedgerTnxType::TRANSFER==$type) {
    $badge_class = 'badge-warning';
}
if(LedgerTnxType::LOSS==$type || LedgerTnxType::PENALTY==$type ) {
    $badge_class = 'badge-danger';
}
if ($transaction->is_manual) {
    $method = "manual";
} else {
    $method = "";
}

@endphp
<div class="nk-tb-col">
    <span class="text-dark">{{ the_tnx($transaction->ivx, 'ivx') }} <span class="badge ml-1 d-sm-none badge-dot {{ $badge_class }}">&nbsp;</span></span>
    <span class="d-sm-none sub-text">{{ show_date($transaction->created_at, true) }}</span>
</div>

<div class="nk-tb-col tb-col-sm">
    <span class="sub-text">{{ show_date($transaction->created_at, true) }}</span>
</div>

<div class="nk-tb-col tb-col-md">
    <span>{{ data_get($transaction, 'desc', '-') }}</span>
    @if(data_get($meta, 'mode') == 'auto')
        <em class="icon ni ni-info-i text-soft fs-13px" data-toggle="tooltip" title="{{ __("Automatic") }}"></em>
    @endif
    @if(!empty($transaction->note) || !empty($transaction->remarks))
        <em class="icon ni ni-info text-soft fs-13px" data-toggle="tooltip" title="{{ $transaction->note }}{{ (!empty($transaction->note) && !empty($transaction->remarks)) ? " || " : "" }}{{ !empty($transaction->remarks) ? __("Remark").": ".$transaction->remarks : "" }}"></em>
    @endif
</div>

@if(!isset($hide_user))
<div class="nk-tb-col tb-col-md">
    <span>{{ the_uid($transaction->user_id) }}</span>
    <em class="icon ni ni-info text-soft fs-13px" data-toggle="tooltip" title="{{ $transaction->user->name }} ({{ str_protect($transaction->user->email) }})"></em>
</div>
@endif

<div class="nk-tb-col tb-col-sm">
    <span class="badge badge-dot {{ $badge_class }}">{{ ucfirst(__($type)) }}</span> {{ ($method) ? __("(M)") : "" }}
</div>

<div class="nk-tb-col text-right">
    <span class="text-dark">{{ calc_sign($transaction->calc) }} {{ money($transaction->amount, base_currency()) }}</span>
    <span class="d-sm-none sub-text">{{ the_uid($transaction->user_id) }}</span>
</div>