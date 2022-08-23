<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'refer_by',
        'join_at',
        'meta',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'join_at' => 'datetime',
        'meta' => 'array',
    ];

    public $timestamps=false;

    public function referred()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
