<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifyToken extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'email',
        'token',
        'code',
        'verify',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @version 1.0.0
     * @since 1.0
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getEmailMd5Attribute()
    {
        return md5($this->email);
    }
}
