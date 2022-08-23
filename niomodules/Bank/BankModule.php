<?php


namespace NioModules\Bank;


use App\Interfaces\Payable;
use App\Models\PaymentMethod;
use App\Enums\PaymentMethodStatus;

class BankModule implements Payable
{
    const VERSION = '1.2.0';
    const LAST_UPDATE = '03122022';
    const MIN_APP_VER = '1.3.0';
    const MOD_TYPES = 'core';
    const SLUG = 'bank-transfer';
    const METHOD = 'bank';

    private $paymentMethod;

    public function __construct()
    {
        $this->paymentMethod = PaymentMethod::where('slug', self::SLUG)->first();
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
     * @version 1.0.0
     * @since 1.0
     */
    public function getTitle()
    {
        return data_get($this->paymentMethod->config, 'meta.title', $this->paymentMethod->name);
    }

    /**
     * @param $transaction
     * @param $paymentMethodDetails
     * @version 1.0.0
     * @since 1.0
     */
    public function makePayment($transaction, $paymentMethodDetails)
    {
        $depositDetails = [
            'amount' => data_get($transaction, 'tnx_total'),
            'currency' => data_get($transaction, 'tnx_currency'),
            'currency_name' => get_currency(data_get($transaction, 'tnx_currency'), 'name'),
            'bank' => data_get($transaction, 'meta.pay_meta'),
            'order' => $transaction,
            'method' => $paymentMethodDetails
        ];

        request()->session()->put('deposit_details', $depositDetails);

        return [
            'redirect' => true,
            'approve_url' => [
                'href' => route('user.bank.deposit.complete')
            ],
        ];
    }

    /**
     * @param $transaction
     * @param $paymentMethodDetails
     * @version 1.0.0
     * @since 1.0
     */
    public function verifyPayment($transaction, $paymentMethodDetails)
    {
        return false;
    }

    /*
     * Check if this active
     */
    public function isActive()
    {
        if (blank($this->paymentMethod)) {
            return false;
        }

        if (empty($this->paymentMethod->currencies)) {
            return false;
        }

        if (!$this->hasConfig()) {
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

        if ($this->paymentMethod->status==PaymentMethodStatus::ACTIVE) {
            return true;
        }

        return false;
    }

    /*
     * Check it has minimal configuration
     */
    public function hasConfig()
    {
        $accountName = data_get($this->paymentMethod, 'config.ac.account_name');
        $accountNumber = data_get($this->paymentMethod, 'config.ac.account_number');
        $bankName = data_get($this->paymentMethod, 'config.ac.bank_name');
        $shortName = data_get($this->paymentMethod, 'config.ac.bank_short');

        if (empty($accountName) || empty($accountNumber) || empty($bankName) || empty($shortName)) {
            return false;
        }

        return true;
    }

    /*
     * Provide payment info
     */
    public function getPayInfo($currency=null, $only=false)
    {
        if (!$this->hasConfig()) return false;

        $config = $this->paymentMethod->config;
        $info = $config['ac'];

        if($only===true) {
            $acc_num = $info['account_number'] ?? '0000';
            $bank_short = $info['bank_short'] ?? 'BANK';
            return str_end($acc_num, $bank_short, ' XXX-', 4);
        }

        return (!empty($info)) ? $info : false;
    }

    public static function getShortcutInfo($transaction)
    {
        $table = '<table width="100%">';
        $data = array_merge(['Payment Reference'=>the_tnx($transaction->tnx)], data_get($transaction, 'meta.pay_meta'));

        foreach ($data as $key=>$value) {
            $table .= $value ? '<tr><td width="150">' . ucwords(str_replace('_', ' ', $key)) . '</td><td width="25">&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>'. $value .'</td></tr>' : '';
        }
        $table .= '</table>';

        return "\nPlease send the amount of " . money($transaction->tnx_total, $transaction->tnx_currency, ['dp'=>'calc']) . " to the bank account as below:\n$table\n\nPlease Note: Make your payment within 3 days, unless this order will be cancelled.";
    }

    public function getDetailsView($transaction, $paymentMethodDetails)
    {
        return view('Bank::details', compact('transaction'));
    }
}
