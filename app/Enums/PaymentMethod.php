<?php


namespace App\Enums;


interface PaymentMethod
{
    const PAYPAL = 'paypal';
    const BANK_TRANSFER = 'bank-transfer';
    const CREDIT_DEBIT_CARD = 'card';
}
