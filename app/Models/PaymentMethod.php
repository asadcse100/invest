<?php

namespace App\Models;

use App\Enums\PaymentMethodStatus;
use App\Services\Transaction\TransactionProcessor;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected static function booted()
    {
        static::saving(function ($paymentMethod) {
            update_method_config($paymentMethod);
        });
    }

    private $transactionProcessor;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->transactionProcessor = new TransactionProcessor();
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
        if ($this->status == PaymentMethodStatus::INACTIVE) {
            return false;
        }

        $paymentProcessor = $this->transactionProcessor->getPaymentProcessor($this);
        if ($paymentProcessor) {
            return $paymentProcessor->isActive();
        }

        return false;
    }

    public function getHasConfigAttribute()
    {
        $paymentProcessor = $this->transactionProcessor->getPaymentProcessor($this);
        if ($paymentProcessor) {
            return $paymentProcessor->hasConfig();
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

    public function getTitleAttribute()
    {
        return (isset($this->config['meta']['title'])) ? $this->config['meta']['title'] : $this->name;
    }


    /**
     * @param $method
     * @param $currency
     * @param $only
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    public static function paymentInfo($method, $currency, $only=true)
    {
        $processor = new TransactionProcessor();
        $paymentProcessor = $processor->getPaymentProcessor($method);

        if ($paymentProcessor) {
            return $paymentProcessor->getPayInfo($currency, $only);
        }
        return false;
    }
}
