<?php

namespace NioModules\WdBank;

use App\Enums\WithdrawMethodStatus;
use App\Interfaces\Withdrawable;
use App\Models\WithdrawMethod;

class WdBankModule implements Withdrawable
{
    const VERSION = '1.2.0';
    const LAST_UPDATE = '03122022';
    const MIN_APP_VER = '1.3.0';
    const MOD_TYPES = 'core';
    const SLUG = 'wd-bank-transfer';
    const METHOD = 'bank';

    private $withdrawMethod;

    public function __construct()
    {
        $this->withdrawMethod = WithdrawMethod::where('slug', self::SLUG)->first();
    }

    /**
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    public function getRequiredMinBaseAppVersion()
    {
        return self::MIN_APP_VER;
    }

    /**
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    public function getSlug()
    {
        return self::SLUG;
    }

    /**
     * @return string
     * @version 1.0.1
     * @since 1.0
     */
    public function getTitle()
    {
        $title = data_get($this->withdrawMethod->config, 'meta.title');
        return (!empty($title)) ? $title : $this->withdrawMethod->name;
    }

    /**
     * @param $transaction
     * @param $methodDetails
     * @return array
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.0
     */
    public function makeWithdraw($transaction, $methodDetails)
    {
        // makeWithdraw() method.
    }

    /**
     * @param $transaction
     * @param $methodDetails
     * @return array
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.0
     */
    public function verifyWithdraw($transaction, $methodDetails)
    {
        // verifyWithdraw() method.
    }

    /*
     * Get Method name or Account from config
     */
    public function getMethod($name=null)
    {
        $name = ($name=='method') ? 'method' : 'account';
        return data_get($this->withdrawMethod->module_config, $name, $this->withdrawMethod->name);
    }

    /*
     * Provide Account Details
     */
    public function getAccountDetails($account)
    {
        $details = "";
        if ($bankName = data_get($account, 'config.bank_name', false)) {
            $details .= $bankName;
        }
        if ($branchName = data_get($account, 'config.bank_branch', false)) {
            $details .= ', '. $branchName;
        }
        if ($accountName = data_get($account, 'config.acc_name', false)) {
            $details .= ' <br>'.$accountName;
        }
        if ($accountNumber = data_get($account, 'config.acc_no', false)) {
            $details .= ' ('.str_end($accountNumber, true, 'XXXX-').')';
        }

        return $details;
    }

    /*
     * Provide Account Infomation
     */
    public function getAccountName($account)
    {
        $details = "";
        $bankName = data_get($account, 'config.bank_name', 'BANK');
        $accNo = data_get($account, 'config.acc_no', false);

        if ($accNo) {
            $details .= strtoupper(str_end($accNo, first_word($bankName), '-XXXX-'));
        }

        return $details;
    }

    /*
     * Provide Currency Infomation
     */
    public function getCurrency($account)
    {
        return data_get($account, 'config.currency');
    }

    /*
     * Check if this active
     */
    public function isActive()
    {
        if (blank($this->withdrawMethod)) {
            return false;
        }

        if (!$this->hasConfig()) {
            return false;
        }

        if (empty($this->withdrawMethod->currencies)) {
            return false;
        }

        return true;
    }

    /*
     * Check if this enable
     */
    public function isEnable()
    {
        if (!$this->isActive()) {
            return false;
        }

        if ($this->withdrawMethod->status==WithdrawMethodStatus::ACTIVE) {
            return true;
        }

        return false;
    }

    /*
     * Check it has minimal configuration
     */
    public function hasConfig()
    {
        return !empty($this->withdrawMethod->config);
    }

    /*
     * Provide payment info
     */
    public function getPayInfo($account, $currency=null, $only=false)
    {
        if (!$this->hasConfig()) return false;
        if(blank($account)) return false;

        $payinfo = [
            'method' => $this->getTitle(),
            'label' => $account->name,
            'payment' => [
                'acc_type' => data_get($account->config, 'acc_type'),
                'acc_name' => data_get($account->config, 'acc_name'),
                'acc_no' => data_get($account->config, 'acc_no'),
                'bank_name' => data_get($account->config, 'bank_name'),
                'bank_branch' => data_get($account->config, 'bank_branch'),
                'bank_address' => data_get($account->config, 'bank_address'),
                'currency' => data_get($account->config, 'currency'),
                'country' => data_get($account->config, 'country'),
                'sortcode' => data_get($account->config, 'sortcode'),
                'iban' => data_get($account->config, 'iban'),
                'routing' => data_get($account->config, 'routing'),
                'swift' => data_get($account->config, 'swift'),
            ],
            'currency' => data_get($account->config, 'currency'),
        ];

        if($only===true) {
            return $payinfo['payment']['acc_no'];
        }

        return (!empty($payinfo)) ? $payinfo : false;
    }

    public static function getShortcutInfo($transaction)
    {
        $data = [
            "Reference ID" => the_tnx($transaction->tnx),
            "Withdraw Amount" => money($transaction->total, sys_settings('base_currency'), ['dp'=>'calc']),
            "Payment Method" => $transaction->method_name,
            "Account Transfer To"=> data_get($transaction, 'meta.account'),
            "Transfer Amount" => money($transaction->tnx_amount, $transaction->tnx_currency, ['dp'=>'calc'])
        ];

        return $data;
    }

    public function getDetailsView($transaction, $wdm)
    {
        return view("WdBank::details", compact('transaction', 'wdm'));
    }
}
