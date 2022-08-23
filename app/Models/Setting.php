<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    const UPDATED_AT = null;
    /**
     * @var string[]
     */
    protected $fillable = [
        'key',
        'value'
    ];

    protected static function booted()
    {
        static::saved(function ($user) {
            if (Cache::has('sys_settings')) {
                Cache::forget('sys_settings');
            }
        });
    }

}
