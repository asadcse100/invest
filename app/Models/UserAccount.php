<?php

namespace App\Models;

use App\Services\Withdraw\WithdrawProcessor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class UserAccount extends Model
{
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
        'user_id',
        'slug',
        'name',
        'config',
        'last_used',
    ];

    protected $casts = [
        'config' => 'array',
        'last_used' => 'datetime',
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

    public function getDescAttribute()
    {
        $message = $this->getWithdrawToAttribute();

        if (!blank($this->last_used)) {
            $message .= ' '. __('(Last Used :date)', ['date' => show_date($this->last_used)]);
        }

        return $message;
    }

    public function getWithdrawToAttribute()
    {
        $message = '';
        if ($this->slug == \App\Enums\PaymentMethod::PAYPAL) {
            $message =  Arr::get($this->config, 'account_number');
        } elseif($this->slug == \App\Enums\PaymentMethod::BANK_TRANSFER){
            $message =  Arr::get($this->config, 'swift').' - '.Arr::get($this->config, 'iban');
        }

        return $message;
    }

    public function getAccountDetailsAttribute()
    {
        return $this->withdrawProcessor->formatAccountDetails($this);
    }

    public function getAccountNameAttribute()
    {
        return $this->withdrawProcessor->formatAccountName($this);
    }

    public function getAccountCurrencyAttribute()
    {
        return $this->withdrawProcessor->getCurrency($this);
    }

    public function getMethodAttribute()
    {
        return $this->withdrawProcessor->getMethod($this, 'method');
    }

    public function getMethodNameAttribute()
    {
        return $this->withdrawProcessor->getMethod($this, 'name');
    }

    /**
     * @param $name
     * @param $userID
     * @return object|models
     * @version 1.0.0
     * @since 1.0
     */
    public static function getAccounts($name=null, $userID=null)
    {
        $user_id = ($userID) ? $userID : auth()->user()->id;

        if(empty($name)) {
            $user_account = self::where('user_id', $user_id)->orderBy('id', 'desc')->get();
        } else {
            $user_account = self::where('slug', $name)->where('user_id', $user_id)->orderBy('id', 'desc')->get();
        }

        return $user_account;
    }

    /**
     * @param $name
     * @return boolean
     * @version 1.0.0
     * @since 1.0
     */
    public static function hasAccounts($name=null, $userID=null)
    {
        $accounts = self::getAccounts($name, $userID);

        return (blank($accounts)) ? false : true;
    }

    /**
     * @param $account
     * @param $method
     * @return mixed|array
     * @version 1.0.0
     * @since 1.0
     */
    public static function paymentInfo($account, $method, $currency=null, $only=true)
    {
        $processor = new WithdrawProcessor();
        $wdmProcessor = $processor->getWithdrawProcessor($method);

        if ($wdmProcessor) {
            return $wdmProcessor->getPayInfo($account, $currency, $only);
        }
        return false;
    }
}
