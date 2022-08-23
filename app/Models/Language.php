<?php

namespace App\Models;

use App\Enums\Boolean;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Language extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'code',
        'label',
        'short',
        'file',
        'rtl',
        'status',
    ];

    protected static function booted()
    {
        static::saved(function ($language) {
            if (Cache::has('lang_dir')) {
                Cache::forget('lang_dir');
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', Boolean::YES);
    }
}
