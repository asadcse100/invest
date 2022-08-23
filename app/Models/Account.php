<?php

namespace App\Models;

use Brick\Math\BigDecimal;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'balance',
        'amount',
    ];

	/**
     * @param $name
     * @param $echo
     * @return mixed|object|models
     * @version 1.0.0
     * @since 1.0
     */
    public static function getBalance($name=null, $userID=null, $echo=false)
    {
        $name = (empty($name)) ? AccType('main') : $name;
        $user_id = ($userID) ? $userID : auth()->user()->id;

        $account = self::where('user_id', $user_id)->where('balance', $name)->first();

        if(blank($account)) return ($echo===true) ? 0 : false;

        return ($echo===true) ? $account->amount : $account;
    }

    /**
     * @param $name
     * @return boolean
     * @version 1.0.0
     * @since 1.0
     */
    public static function hasBalance($name=null, $userID=null)
    {
        $name = (empty($name)) ? AccType('main') : $name;
        $balance = self::getBalance($name, $userID, true);
        if(empty($balance)) return false;

        return (BigDecimal::of($balance)->compareTo('0') > 0) ? true : false;
    }
}
