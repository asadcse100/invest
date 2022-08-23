<?php


namespace App\Enums;


interface UserStatus
{
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    const LOCKED = 'locked';
    const SUSPEND = 'suspend';
    const DELETED = 'deleted';
}
