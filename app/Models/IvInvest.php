<?php

namespace App\Models;

use App\Models\User;
use App\Models\Transaction;
use App\Filters\Filterable;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use App\Enums\InterestPeriod;
use App\Enums\InterestRateType;
use App\Enums\InvestmentStatus;

class IvInvest extends Model
{
    use Filterable;

    protected $fillable = [
        "ivx",
        "user_id",
        "amount",
        "profit",
        "total",
        "received",
        "currency",
        "rate",
        "term",
        "term_count",
        "term_total",
        "term_calc",
        "term_start",
        "term_end",
        "reference",
        "scheme",
        "scheme_id",
        "meta",
        "desc",
        "remarks",
        "note",
        "status",
        "scheme_id"
    ];

    protected $casts = [
        'term_start' => 'datetime',
        'term_end' => 'datetime',
        'order_at' => 'datetime',
        'approved_at' => 'datetime',
        'profit' => 'array',
        'scheme' => 'array',
        'meta' => 'array',
    ];

    private $interval = [
        InterestPeriod::HOURLY => '1 hour',
        InterestPeriod::DAILY => '1 day',
        InterestPeriod::WEEKLY => '1 week',
        InterestPeriod::MONTHLY => '1 month',
        InterestPeriod::YEARLY => '1 year',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ledgers()
    {
        return $this->hasMany(IvLedger::class, 'invest_id')
            ->orderBy('iv_ledgers.created_at', 'asc')
            ->orderBy('iv_ledgers.id', 'asc');
    }

    public function ledger()
    {
        return $this->hasOne(IvLedger::class, 'reference', 'ivx');
    }

    public function actions()
    {
        return $this->hasMany(IvAction::class, 'type_id')->where('type', 'invest');
    }

    public function action_by($type)
    {
        $action = $this->actions->where('action', $type)->last();

        if(!blank($action)) {
            return $action;
        }
        return false;
    }

    public function get_action($type, $what=null)
    {
        $action = $this->action_by($type);
        $what = ($what=='by') ? 'action_by' : 'action_at';

        if(!empty($action)) {
            return data_get($action, $what, false);
        }
        return false;
    }

    public function profits()
    {
        return $this->hasMany(IvProfit::class, 'invest_id');
    }

    public function ivScheme()
    {
        return $this->belongsTo(IvScheme::class, 'scheme_id');
    }

    public function scopeLoggedUser($query)
    {
        return $query->where('user_id', auth()->user()->id);
    }

    public function getSummaryTitleAttribute()
    {
        $type_text = (data_get($this->scheme, 'term') == 1) ? Str::singular(data_get($this->scheme, 'term_type')) : data_get($this->scheme, 'term_type');

        return sprintf(
            '%s - %s %s%s for %d %s',
            data_get($this->scheme, 'name'),
            ucfirst(data_get($this->scheme, 'calc_period')),
            data_get($this->scheme, 'rate'),
            data_get($this->scheme, 'rate_type') == InterestRateType::PERCENT ? '%' : ' '.strtoupper($this->currency),
            data_get($this->scheme, 'term'),
            ucfirst($type_text)
        );
    }

    
    public function getSummaryTitleAlterAttribute()
    {
        $rate_type = (data_get($this->scheme, 'rate_type') == InterestRateType::PERCENT) ? '%' : ' '.strtoupper($this->currency);
        $type_text = (data_get($this->scheme, 'term') == 1) ? Str::singular(data_get($this->scheme, 'term_type')) : data_get($this->scheme, 'term_type');

        return __(":Name - :Period :rate for :term :Type", [
            'name' => __(data_get($this->scheme, 'name')), 
            'period' => __(data_get($this->scheme, 'calc_period')), 
            'rate' => data_get($this->scheme, 'rate').$rate_type, 
            'term' => data_get($this->scheme, 'term'), 
            'type' => __($type_text)
        ]);
    }

    public function getCalcDetailsAttribute()
    {
        return sprintf(
            '%s %s%s for %d %s',
            ucfirst(data_get($this->scheme, 'calc_period')),
            data_get($this->scheme, 'rate'),
            data_get($this->scheme, 'rate_type') == InterestRateType::PERCENT ? '%' : ' '.strtoupper($this->currency),
            data_get($this->scheme, 'term'),
            ucfirst(data_get($this->scheme, 'term_type'))
        );
    }

    public function getCalcDetailsAlterAttribute()
    {
        $rate_type = (data_get($this->scheme, 'rate_type') == InterestRateType::PERCENT) ? '%' : ' '.strtoupper($this->currency);
        $type_text = (data_get($this->scheme, 'term') == 1) ? Str::singular(data_get($this->scheme, 'term_type')) : data_get($this->scheme, 'term_type');

        return __(":Period :rate for :term :Type", [
            'period' => __(data_get($this->scheme, 'calc_period')), 
            'rate' => data_get($this->scheme, 'rate').$rate_type, 
            'term' => data_get($this->scheme, 'term'), 
            'type' => __($type_text)
        ]);
    }

    public function getOrderAtAttribute()
    {
        return $this->get_action('order', 'at');
    }

    public function getOrderByAttribute()
    {
        return $this->get_action('order', 'by');
    }

    public function getApproveAtAttribute()
    {
        return $this->get_action('active', 'at');
    }

    public function getApproveByAttribute()
    {
        return $this->get_action('active', 'by');
    }

    public function getCompletedAtAttribute()
    {
        return $this->get_action('complete', 'at');
    }

    public function getCompletedByAttribute()
    {
        return $this->get_action('complete', 'by');
    }

    public function getCancelledAtAttribute()
    {
        return $this->get_action('cancel', 'at');
    }

    public function getCancelledByAttribute()
    {
        return $this->get_action('cancel', 'by');
    }

    public function getPaymentSourceAttribute()
    {
        return data_get($this->ledger, 'source');
    }

    public function getPaymentDestAttribute()
    {
        return data_get($this->ledger, 'dest');
    }
    
    public function getPaymentDateAttribute()
    {
        return show_date(data_get($this->ledger, 'created_at'), true);
    }

    public function getPaidAmountAttribute()
    {
        return data_get($this->ledger, 'total');
    }

    public function getProfitLockedAttribute()
    {
        return $this->profits->whereNull('payout')->sum('amount');
    }

    public function getPendingAmountAttribute()
    {
        return ($this->total - $this->received);
    }

    public function getCodeAttribute()
    {
        $shortname = data_get($this, 'scheme.short');

        return substr($shortname, 0, 2);
    }

    public function getRateTextAttribute()
    {
        $currency = base_currency();
        if(data_get($this->scheme, 'rate_type') == InterestRateType::FIXED) {
            return $currency. ' '.amount_z(data_get($this->scheme, 'rate'), $currency);
        }

        return data_get($this->scheme, 'rate') . '%';
    }

    public function getTermTextAttribute()
    {
        $term = data_get($this->scheme, 'term');
        $term_type = (data_get($this->scheme, 'term') == 1) ? Str::singular(data_get($this->scheme, 'term_type')) : data_get($this->scheme, 'term_type');
        
        return __(":term :type", ['term' => $term, 'type' => ucfirst($term_type)]);
    }

    public function getTermTextAlterAttribute()
    {
        $term = data_get($this->scheme, 'term');
        $term_type = (data_get($this->scheme, 'term') == 1) ? Str::singular(data_get($this->scheme, 'term_type')) : data_get($this->scheme, 'term_type');

        return __(':term :Type', ['term' => $term, 'type' => __($term_type)]);
    }

    public function getCalcProfitAttribute()
    {
        $profit = (data_get($this->scheme, 'rate_type') == InterestRateType::FIXED) ? data_get($this->scheme, 'rate')  : ($this->amount * data_get($this->scheme, 'rate') / 100);

        if (data_get($this, 'scheme.capital') == 0) {
            $capital = ($this->amount / $this->term_total);
            return ($profit + $capital);
        }

        return $profit;
    }

    public function getPeriodTextAttribute()
    {
        $calcPeriod = data_get($this, 'scheme.calc_period');

        switch ($calcPeriod) {
            case InterestPeriod::HOURLY:
                return __('Per Hour');
            case InterestPeriod::DAILY:
                return __('Per Day');
            case InterestPeriod::WEEKLY:
                return __('Per Week');
            case InterestPeriod::MONTHLY:
                return __('Per Month');
            case InterestPeriod::YEARLY:
                return __('Per Year');
        }
    }

    public function getCalcPeriodAttribute()
    {
        $calcPeriod = data_get($this, 'scheme.calc_period');
        return str_replace(__('Per '), '', $this->getPeriodTextAttribute());
    }

    public function getProgressAttribute()
    {
        $percent = 0;
        if (!empty($this->term_count) && !empty($this->term_total)) {
            $percent = round((($this->term_count/$this->term_total) * 100), 2);
        }

        return ($percent > 99) ? 100 : $percent;
    }

    private function calculateIntervals($tillNow = false)
    {
        if (empty($this->term_start) || empty($this->term_end)) {
            return [];
        }
        $end = ($tillNow) ? (Carbon::now()->gt($this->term_end) ? $this->term_end : Carbon::now()) : $this->term_end;
        $interval = $this->interval[data_get($this, 'scheme.calc_period')];
        $intervalPeriod = CarbonPeriod::create($this->term_start, $interval, $end)->toArray();
        array_shift($intervalPeriod);

        // Limited if Interval over than term count.
        if(count($intervalPeriod) >= $this->term_total) {
            $intervalPeriod = array_slice($intervalPeriod, 0, $this->term_total);
        }
        return $intervalPeriod;
    }

    public function getTotalPeriodIntervalAttribute()
    {
        return $this->calculateIntervals();
    }

    public function getPeriodIntervalElapsedAttribute()
    {
        return $this->calculateIntervals(true);
    }

    public function getRemainingPeriodIntervalCountAttribute()
    {
        return (count($this->getTotalPeriodIntervalAttribute()) - count($this->getPeriodIntervalElapsedAttribute()));
    }

    public function getRemainingTermAttribute()
    {
        $remain = ($this->term_total - $this->term_count);

        return ($remain > 0) ? $remain : 0;
    }

    public function getPayoutTypeAttribute()
    {
        return data_get($this, 'scheme.payout');
    }

    public function getRemainingPeriodTextAttribute(): string
    {
        return $this->getRemainingPeriodIntervalCountAttribute() . ' ' . strtoupper(substr(data_get($this, 'scheme.calc_period'), 0, 1));
    }

    public function scopeIsActive($query)
    {
        return $query->where('status', InvestmentStatus::ACTIVE);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'DESC');
    }

    public function scopeSumProfit($query)
    {
        return $query->sum('profit');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereBetween('term_start', [
            Carbon::now()->startOfMonth()->tz(time_zone()), 
            Carbon::now()->endOfMonth()->tz(time_zone())
        ]);
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('term_start', [
            Carbon::now()->startOfWeek()->tz(time_zone()), 
            Carbon::now()->endOfWeek()->tz(time_zone())
        ]);
    }

    public function scopeLastWeek($query)
    {
        return $query->whereBetween('term_start', [
            Carbon::now()->subWeek()->startOfWeek()->tz(time_zone()),
            Carbon::now()->subWeek()->endOfWeek()->tz(time_zone())
        ]);
    }

    public function scopeFromLastWeek($query)
    {
        return $query->whereBetween('term_start', [
            Carbon::now()->subWeek()->startOfWeek()->tz(time_zone()),
            Carbon::now()->tz(time_zone())
        ]);
    }

    public function scopeLastMonth($query)
    {
        return $query->whereBetween('term_start', [
            Carbon::now()->subMonth()->startOfMonth()->tz(time_zone()),
            Carbon::now()->subMonth()->endOfMonth()->tz(time_zone())
        ]);
    }

    public function scopeThisYear($query)
    {
        return $query->whereBetween('term_start', [
            Carbon::now()->startOfYear()->tz(time_zone()),
            Carbon::now()->endOfYear()->tz(time_zone())
        ]);
    }

    public function scopeIsValid($query)
    {
        return $query->where('status',InvestmentStatus::ACTIVE)
                    ->orWhere('status',InvestmentStatus::COMPLETED);
    }

    public function scopeLastYear($query)
    {
        return $query->whereBetween('term_start', [
            Carbon::now()->subYear()->startOfYear()->tz(time_zone()),
            Carbon::now()->subYear()->endOfYear()->tz(time_zone())
        ]);
    }

    public function scopeLastDays($query, $days)
    {
        return $query->whereBetween('term_start', [
            Carbon::now()->subDays($days)->tz(time_zone()),
            Carbon::now()->tz(time_zone())
        ]);
    }

    public function getUserCanCancelAttribute(): bool
    {
        if ($this->status != InvestmentStatus::PENDING) {
            return false;
        }

        $cancelTimeout = sys_settings('iv_cancel_timeout', 15);
        
        if (is_numeric($cancelTimeout)) {
            $elapsedTime = $this->created_at->diffInMinutes(Carbon::now());
            if ($elapsedTime >= $cancelTimeout) {
                return false;
            }
        }

        return true;
    }
}
