<?php


namespace App\Enums;


interface TransactionStatus
{
    const NONE = 'none';
    const PENDING = 'pending';
    const ONHOLD = 'onhold';
    const CONFIRMED = 'confirmed';
    const CANCELLED = 'cancelled';
    const FAILED = 'failed';
    const COMPLETED = 'completed';
}
