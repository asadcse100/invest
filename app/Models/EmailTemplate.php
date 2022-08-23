<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'slug',
        'subject',
        'greeting',
        'content',
        'group',
        'recipient',
        'addresses',
        'params',
        'status',
        'shortcut',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'addresses' => 'array',
        'params' => 'array',
    ];
}
