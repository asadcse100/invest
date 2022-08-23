<?php

namespace App\Models;

use App\Models\User;
use App\Filters\Filterable;
use App\Enums\LedgerTnxType;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class IvLedger extends Model
{
    use Filterable;

    protected $fillable = [
        'ivx',
        'user_id',
        'type',
        'calc',
        'amount',
        'fees',
        'total',
        'currency',
        'desc',
        'remarks',
        'note',
        'invest_id',
        'tnx_id',
        'reference',
        'meta',
        'source',
        'dest',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invest()
    {
        return $this->belongsTo(IvInvest::class, 'invest_id');
    }

    public function scopeLoggedUser($query)
    {
        return $query->where('user_id', auth()->user()->id);
    }

    public function scopeIsInvestment($query)
    {
        return $query->where('type', LedgerTnxType::INVEST);
    }

    public function scopeIsProfit($query)
    {
        return $query->where('type', LedgerTnxType::PROFIT);
    }

    public function scopeIsTransfer($query)
    {
        return $query->where('type', LedgerTnxType::TRANSFER);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereBetween('created_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ]);
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ]);
    }

    public function scopeLastWeek($query)
    {
        return $query->whereBetween('created_at', [
            Carbon::now()->subWeek()->startOfWeek(),
            Carbon::now()->subWeek()->endOfWeek()
        ]);
    }

    public function scopeFromLastWeek($query)
    {
        return $query->whereBetween('created_at', [
            Carbon::now()->subWeek()->startOfWeek(),
            Carbon::now()
        ]);
    }

    public function scopeLastMonth($query)
    {
        return $query->whereBetween('created_at', [
            Carbon::now()->subMonth()->startOfMonth(),
            Carbon::now()->subMonth()->endOfMonth()
        ]);
    }

    public function scopeThisYear($query)
    {
        return $query->whereBetween('created_at', [
            Carbon::now()->startOfYear(),
            Carbon::now()->endOfYear()
        ]);
    }

    public function scopeLastYear($query)
    {
        return $query->whereBetween('created_at', [
            Carbon::now()->subYear()->startOfYear(),
            Carbon::now()->subYear()->endOfYear()
        ]);
    }

    public static function statistics()
    {
        $this30day  = [Carbon::now()->subDays(30)->startOfDay(), Carbon::now()->today()->endOfDay()];
        $last30day  = [Carbon::now()->subDays(60)->startOfDay(), Carbon::now()->subDays(30)->endOfDay()];

        $thisWeek   = [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
        $lastWeek   = [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()];
        $thisMonth  = [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];
        $lastMonth  = [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()];
        $thisYear   = [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()];
        $lastYear   = [Carbon::now()->subYear()->startOfYear(), Carbon::now()->subYear()->endOfYear()];

        $data = [
            '30day' => [
                'plan' => self::isInvestment()->whereBetween('created_at', $this30day)->count(),
                'amount' => self::isInvestment()->whereBetween('created_at', $this30day)->sum('amount'),
                'profit' => self::isProfit()->whereBetween('created_at', $this30day)->sum('amount'),
                'transfer' => self::isTransfer()->whereBetween('created_at', $this30day)->sum('amount'),
                'last' => [
                    'plan' => self::isInvestment()->whereBetween('created_at', $last30day)->count(),
                    'amount' => self::isInvestment()->whereBetween('created_at', $last30day)->sum('amount'),
                    'profit' => self::isProfit()->whereBetween('created_at', $last30day)->sum('amount'),
                    'transfer' => self::isTransfer()->whereBetween('created_at', $last30day)->sum('amount'),
                ]
            ],
            'week' => [
                'plan' => self::isInvestment()->whereBetween('created_at', $thisWeek)->count(),
                'amount' => self::isInvestment()->whereBetween('created_at', $thisWeek)->sum('amount'),
                'profit' => self::isProfit()->whereBetween('created_at', $thisWeek)->sum('amount'),
                'transfer' => self::isTransfer()->whereBetween('created_at', $thisWeek)->sum('amount'),
                'last' => [
                    'plan' => self::isInvestment()->whereBetween('created_at', $lastWeek)->count(),
                    'amount' => self::isInvestment()->whereBetween('created_at', $lastWeek)->sum('amount'),
                    'profit' => self::isProfit()->whereBetween('created_at', $lastWeek)->sum('amount'),
                    'transfer' => self::isTransfer()->whereBetween('created_at', $lastWeek)->sum('amount'),
                ]
            ],
            'month' => [
                'plan' => self::isInvestment()->whereBetween('created_at', $thisMonth)->count(),
                'amount' => self::isInvestment()->whereBetween('created_at', $thisMonth)->sum('amount'),
                'profit' => self::isProfit()->whereBetween('created_at', $thisMonth)->sum('amount'),
                'transfer' => self::isTransfer()->whereBetween('created_at', $thisMonth)->sum('amount'),
                'last' => [
                    'plan' => self::isInvestment()->whereBetween('created_at', $lastMonth)->count(),
                    'amount' => self::isInvestment()->whereBetween('created_at', $lastMonth)->sum('amount'),
                    'profit' => self::isProfit()->whereBetween('created_at', $lastMonth)->sum('amount'),
                    'transfer' => self::isTransfer()->whereBetween('created_at', $lastMonth)->sum('amount'),
                ]
            ],
            'year' => [
                'plan' => self::isInvestment()->whereBetween('created_at', $thisYear)->count(),
                'amount' => self::isInvestment()->whereBetween('created_at', $thisYear)->sum('amount'),
                'profit' => self::isProfit()->whereBetween('created_at', $thisYear)->sum('amount'),
                'transfer' => self::isTransfer()->whereBetween('created_at', $thisYear)->sum('amount'),
                'last' => [
                    'plan' => self::isInvestment()->whereBetween('created_at', $lastYear)->count(),
                    'amount' => self::isInvestment()->whereBetween('created_at', $lastYear)->sum('amount'),
                    'profit' => self::isProfit()->whereBetween('created_at', $lastYear)->sum('amount'),
                    'transfer' => self::isTransfer()->whereBetween('created_at', $lastYear)->sum('amount'),
                ]
            ],
            'all' => [
                'plan' => self::isInvestment()->count(),
                'amount' => self::isInvestment()->sum('amount'),
                'profit' => self::isProfit()->sum('amount'),
                'transfer' => self::isTransfer()->sum('amount'),
            ]
        ];

        $indexes = ['30day', 'week', 'month', 'year'];

        foreach ($indexes as $key) {
            $data[$key]['diff'] = [
                'plan' => to_dfp($data[$key]['plan'], $data[$key]['last']['plan']),
                'amount' => to_dfp($data[$key]['amount'], $data[$key]['last']['amount']),
                'profit' => to_dfp($data[$key]['profit'], $data[$key]['last']['profit']),
                'transfer' => to_dfp($data[$key]['transfer'], $data[$key]['last']['transfer']),
            ];
        }


        return $data;
    }

    public function getIsManualAttribute()
    {
        $meta = (!empty($this->meta) && is_json($this->meta)) ? json_decode($this->meta, true) : false;

        if ($meta) {
            return data_get($meta, 'method') == 'manual' ? true : false;
        }

        return false;
    }

    public function getMetaEntryAttribute()
    {
        $meta = (!empty($this->meta) && is_json($this->meta)) ? json_decode($this->meta, true) : false;

        if ($meta) {
            return data_get($meta, 'entry', false);
        }

        return false;
    }
}
