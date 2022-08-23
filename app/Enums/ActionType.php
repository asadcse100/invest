<?php


namespace App\Enums;


interface ActionType
{
    const ORDER = 'order';
    const REFUND = 'refund';
    const TRANSFER = 'transfer';
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_CANCEL = 'cancel';
    const STATUS_COMPLETE = 'complete';
}
