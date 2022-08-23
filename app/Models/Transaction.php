<?php

namespace App\Models;

use App\Enums\TransactionCalcType;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Filters\Filterable;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class Transaction extends Model
{
    use Filterable, SoftDeletes;
    /**
     * @var string[]
     */
    protected $fillable = [];

    protected $casts = [
        'meta' => 'array',
        'confirmed_by' => 'array',
        'completed_by' => 'array',
        'completed_at' => 'datetime'
    ];

    public function ledger()
    {
        return $this->hasOne(Ledger::class);
    }

    public function getMethodSlugAttribute()
    {
        $method = '';

        if (in_array($this->type, [TransactionType::DEPOSIT, TransactionType::WITHDRAW])) {
            $method = Arr::get($this->meta, 'method');
        }

        if ($this->type == TransactionType::INVESTMENT) {
            $method = AccType('main');
        }

        return $method;
    }

    public function getMethodIconClassAttribute()
    {
        return config("modules.{$this->method_slug}.icon", "");
    }

    public function getIsOnlineAttribute()
    {
        return config("modules.{$this->method_slug}.is_online");
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transaction_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTypeOfFundAttribute()
    {
        $fundType = '';

        switch ($this->type) {
            case TransactionType::DEPOSIT:
                $fundType = __('Deposit Funds');
                break;
            case TransactionType::WITHDRAW:
                $fundType = __('Withdraw Funds');
                break;
            case TransactionType::BONUS:
                $fundType = __('Bonus Added');
                break;
            case TransactionType::REFERRAL:
                $fundType = __('Referral Bonus');
                break;
            case TransactionType::TRANSFER:
                $fundType = __('Send Funds');
                break;
            case TransactionType::CHARGE:
                $fundType = __('Charge to Account');
                break;
            case TransactionType::INVESTMENT:
                $fundType = ($this->calc == TransactionCalcType::CREDIT) ? __('Received from Investment Account') : __('Invested on Plan');
                break;
        }

        return $fundType;
    }

    public function getMethodNameAttribute()
    {
        $unknown = __("Unknown");

        if (in_array($this->type, [TransactionType::DEPOSIT, TransactionType::WITHDRAW])) {
            return config("modules.{$this->method_slug}.name", $unknown);
        } elseif (in_array($this->type, [TransactionType::CHARGE, TransactionType::BONUS, TransactionType::REFERRAL, TransactionType::TRANSFER])) {
            return from_to_case($this->tnx_method);
        } elseif (in_array($this->type, [TransactionType::INVESTMENT])) {
            return __("Internal");
        } else {
            return $unknown;
        }
    }

    public function getMethodTitleAttribute()
    {
        return Arr::get($this->meta, 'method');
    }

    public function getReferralUserAttribute()
    {
        return Arr::get($this->meta, 'referral.user', false);
    }

    public function getShortcutDetailsAttribute()
    {
        if ($this->type == TransactionType::WITHDRAW) {
            if (config('modules')[$this->tnx_method]["processor"]) {
                return call_user_func(config('modules')[$this->tnx_method]["processor"] . "::getShortcutInfo", $this);
            }
            return "";
        }

        $data["Payment Reference"] = the_tnx($this->tnx);
        $data["Payment Amount"] = money($this->tnx_total, $this->tnx_currency, ['dp' => 'calc']);
        $data["Payment Method"] = $this->method_name;

        if ($this->type == TransactionType::TRANSFER) {
            unset($data["Payment Method"]);
            $data["Payment To"] = get_user(data_get($this, 'meta.transfer.user'))->email;
            if ($this->note) {
                $data["Payment Note"] = $this->note;
            }
        } else {
            $data["Amount to Credit"] = money($this->amount, $this->currency, ['dp' => 'calc']);
        }

        return $data;
    }

    public function getPaymentInfoAttribute()
    {
        if (in_array($this->tnx_method, config('modules'))) {
            if (config('modules')[$this->tnx_method]["processor"]) {
                return call_user_func(config('modules')[$this->tnx_method]["processor"] . "::getShortcutInfo", $this);
            }
        }
        return "";
    }

    public function getDetailsAttribute()
    {
        $base_currency = base_currency();
        $data = [
            'id' => $this->id,
            'symbol' => $this->calc == TransactionCalcType::CREDIT ? '+' : '-',
            // system
            'amount' => money($this->amount, $base_currency, ['dp' => 'calc']),
            'amount_num' => $this->amount,
            'currency' => $base_currency,
            'fees' => ($this->fees) ? money($this->fees, $base_currency, ['dp' => 'calc']) : 0,
            'total' => money($this->total, $base_currency, ['dp' => 'calc']),
            // original
            'currency_base' => $this->currency,
            'amount_base' => money($this->amount, $this->currency, ['dp' => 'calc']),
            'fees_base' => ($this->fees) ? money($this->fees, $this->currency, ['dp' => 'calc']) : 0,
            'total_base' => money($this->total, $this->currency, ['dp' => 'calc']),
            // user tnx
            'tnx_amount' => money($this->tnx_amount, $this->tnx_currency, ['dp' => 'calc']),
            'tnx_total' => money($this->tnx_total, $this->tnx_currency, ['dp' => 'calc']),
            'tnx_fees' => ($this->tnx_fees) ? money($this->tnx_fees, $this->tnx_currency, ['dp' => 'calc']) : 0,
            'tnx_currency' => $this->tnx_currency,
            'tnx_icon' => $this->method_icon_class,
            'status' => ($this->status == TransactionStatus::ONHOLD || ($this->status == TransactionStatus::PENDING && $this->type==TransactionType::INVESTMENT)) ? __("Processing") : ucfirst($this->status),
            'order_id' => the_tnx($this->tnx),
            'order_date' => addslashes(show_date($this->created_at, true)),
            'exchange_rate' => money($this->exchange, $this->tnx_currency, ['dp' => 'calc']),
            'base_currency' => $base_currency,
            'type' => ucfirst($this->type),
            'gateway' => $this->method_name,
            'is_online' => $this->is_online ? 1 : 0,
            'pay_from' => $this->pay_from ?? '',
            'pay_to' => $this->pay_to ?? '',
            'reference' => $this->reference,
            'details' => $this->description,
            'notes' => isset($this->note) ? $this->note : '',
        ];
        return collect($data);
    }

    public function scopeLoggedUser($query)
    {
        return $query->where('user_id', auth()->user()->id);
    }

    public function getIsCancellableAttribute()
    {
        if ($this->status != TransactionStatus::PENDING) {
            return false;
        }

        $cancelTimeout = ($this->type == TransactionType::DEPOSIT) ? sys_settings('deposit_cancel_timeout') : sys_settings('withdraw_cancel_timeout');
        if ($cancelTimeout === 'yes') {
            return true;
        }
        if ($cancelTimeout === 0) {
            return false;
        }

        return (remaining_timeout($this->created_at, $cancelTimeout) > 0);
    }


    public function scopeCompleted($query)
    {
        return $query->where('status', TransactionStatus::COMPLETED);
    }

    public function scopeDeposit($query)
    {
        return $query->where('type', TransactionType::DEPOSIT);
    }

    public function scopeWithdraw($query)
    {
        return $query->where('type', TransactionType::WITHDRAW);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereBetween('completed_at', [
            Carbon::now()->startOfMonth()->tz(time_zone()),
            Carbon::now()->endOfMonth()->tz(time_zone())
        ]);
    }

    public function scopeLastMonth($query)
    {
        return $query->whereBetween('completed_at', [
            Carbon::now()->subMonth()->startOfMonth()->tz(time_zone()),
            Carbon::now()->subMonth()->endOfMonth()->tz(time_zone())
        ]);
    }

    public function scopeSumAmount($query)
    {
        return $query->select($query->raw('SUM(amount) as amount,completed_at'));
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('completed_at', [
            Carbon::now()->startOfWeek()->tz(time_zone()),
            Carbon::now()->endOfWeek()->tz(time_zone())
        ]);
    }

    public function scopeLastWeek($query)
    {
        return $query->whereBetween('completed_at', [
            Carbon::now()->subWeek()->startOfWeek()->tz(time_zone()),
            Carbon::now()->subWeek()->endOfWeek()->tz(time_zone())
        ]);
    }

    public function scopeByCompleted($query)
    {
        return $query->groupBy(DB::RAW('CAST(completed_at as DATE)'));
    }

    public function scopeCredits($query)
    {
        return $query->where('calc', TransactionCalcType::CREDIT);
    }

    public function scopeDebits($query)
    {
        return $query->where('calc', TransactionCalcType::DEBIT);
    }

    public function scopeInvestments($query)
    {
        return $query->where('type', TransactionType::INVESTMENT);
    }
}
