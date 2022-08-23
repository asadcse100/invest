<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IvAction extends Model
{
    public $timestamps = false;

    protected $fillable = [
        "action",
        "action_at",
        "action_by",
        "type",
        "type_id"
    ];

    protected $casts = [
        'action_at' => 'datetime',
    ];
}
