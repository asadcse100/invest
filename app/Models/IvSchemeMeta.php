<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class IvSchemeMeta extends Model
{
    protected $fillable = [
        'scheme_id',
        'key',
        'value'
    ];

    public function scheme()
    {
        return $this->belongsTo(IvScheme::class, 'id', 'scheme_id');
    }
}
