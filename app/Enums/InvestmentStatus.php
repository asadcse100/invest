<?php


namespace App\Enums;


interface InvestmentStatus
{
    const PENDING = 'pending';
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    const COMPLETED = 'completed';
    const CANCELLED = 'cancelled';
}
