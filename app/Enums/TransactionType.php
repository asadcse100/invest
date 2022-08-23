<?php


namespace App\Enums;


interface TransactionType
{
    const BONUS = 'bonus';
    const CHARGE = 'charge';
    const DEPOSIT = 'deposit';
    const WITHDRAW = 'withdraw';
    const INVESTMENT = 'investment';
    const REFERRAL = 'referral';
    const TRANSFER = 'transfer';
}
