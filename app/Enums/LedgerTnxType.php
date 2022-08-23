<?php

namespace App\Enums;

interface LedgerTnxType
{
    const PROFIT = 'profit';
    const INVEST = 'invest';
    const TRANSFER = 'transfer';
    const CAPITAL = 'capital';
    const LOSS = 'loss';
    const PENALTY = 'penalty';
}
