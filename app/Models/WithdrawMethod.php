<?php

namespace App\Models;

use App\Enums\WithdrawMethodStatus;
use App\Services\Withdraw\WithdrawProcessor;
use Illuminate\Database\Eloquent\Model;

class WithdrawMethod extends Model
{
    protected static function booted()
    {
        static::saving(function ($withdrawMethod) {
            update_method_config($withdrawMethod);
        });
    }
    
    private $withdrawProcessor;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->withdrawProcessor = new WithdrawProcessor();
    }

    /**
     * @var string[]
     */
    protected $fillable = [
        'slug',
        'name',
        'desc',
        'config',
        'fees',
        'currencies',
        'countries',
        'status',
        'min_amount',
        'max_amount'
    ];

    protected $casts = [
        'config' => 'array',
        'fees' => 'array',
        'currencies' => 'array',
        'countries' => 'array',
    ];

    public function getModuleConfigAttribute()
    {
        return collect(config('modules', []))->where('slug', $this->slug)->first();
    }

    public function getIsActiveAttribute()
    {
        if ($this->status == WithdrawMethodStatus::INACTIVE) {
            return false;
        }

        $withdrawProcessor = $this->withdrawProcessor->getWithdrawProcessor($this);
        if ($withdrawProcessor) {
            return $withdrawProcessor->isActive();
        }

        return false;
    }

    public function getHasConfigAttribute()
    {
        $withdrawProcessor = $this->withdrawProcessor->getWithdrawProcessor($this);
        if ($withdrawProcessor) {
            return $withdrawProcessor->hasConfig();
        }
    }

    public function getMethodAttribute()
    {
        $config = $this->getModuleConfigAttribute();

        return (isset($config['method'])) ? $config['method'] : null;
    }

    public function getModuleAttribute()
    {
        $config = $this->getModuleConfigAttribute();

        return (isset($config['processor_type'])) ? $config['processor_type'] : null;
    }

    public function getAccountAttribute()
    {
        $config = $this->getModuleConfigAttribute();

        return (isset($config['account'])) ? $config['account'] : $config['name'];
    }

    public function getTitleAttribute()
    {
        return (isset($this->config['meta']['title'])) ? $this->config['meta']['title'] : $this->name;
    }
}
