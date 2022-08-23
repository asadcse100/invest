<?php


namespace App\Enums;


interface AccountBalanceType
{
    const MAIN = 'main_wallet';
    const INVEST = 'invest_wallet';
    const REFERRAL = 'referral_account';
    const MAIN_HOLD = 'main_wallet_hold';
    const INVEST_HOLD = 'invest_wallet_hold';
}
