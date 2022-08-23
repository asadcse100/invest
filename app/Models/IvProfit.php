<?php

namespace App\Models;

use App\Models\User;
use App\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;

class IvProfit extends Model
{
    use Filterable;

    protected $fillable = [
        "user_id",
        "invest_id",
        "amount",
        "capital",
        "invested",
        "currency",
        "rate",
        "type",
        "payout",
        "term_no",
        "calc_at",
    ];

    protected $casts = [
        'calc_at' => 'datetime',
    ];

    public function invest_by()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function invest()
    {
        return $this->belongsTo(IvInvest::class, 'invest_id');
    }
}
