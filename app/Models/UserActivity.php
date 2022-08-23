<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'session',
        'ip',
        'meta',
        'browser',
        'device',
        'platform',
        'version',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'version' => 'array',
        'session' => 'datetime'
    ];

    /**
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    public function getBrowserWithPlatformAttribute()
    {
        return "{$this->browser} on {$this->platform}";
    }
}
