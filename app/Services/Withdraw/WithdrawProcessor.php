<?php


namespace App\Services\Withdraw;


use App\Interfaces\Withdrawable;
use App\Models\WithdrawMethod;

class WithdrawProcessor
{
    private function getWithdrawMethod($withdrawMethod)
    {
        return WithdrawMethod::where('slug', $withdrawMethod)->first();
    }

    public function getWithdrawProcessor($withdrawMethod)
    {
        $withdrawProcessor = data_get($withdrawMethod, 'module_config.processor');

        if (!blank($withdrawProcessor) && class_exists($withdrawProcessor)) {
            $withdrawProcessor =  new $withdrawProcessor();
            if ($withdrawProcessor instanceof Withdrawable) {
                return $withdrawProcessor;
            } else {
                throw new \Exception(__("Withdraw processor must be an instance of Withdrawable"));
            }
        }

        return false;
    }

    public function process($transaction, $withdrawMethod)
    {
        $withdrawMethodDetails = $this->getWithdrawMethod($withdrawMethod);
        $withdrawProcessor = $this->getWithdrawProcessor($withdrawMethodDetails);

        if ($withdrawProcessor) {
            return $withdrawProcessor->makeWithdraw($transaction, $withdrawMethodDetails);
        }

        return false;
    }

    public function verify($transaction, $withdrawMethod)
    {
        $withdrawMethodDetails = $this->getWithdrawMethod($withdrawMethod);
        $withdrawProcessor = $this->getWithdrawProcessor($withdrawMethodDetails);

        if ($withdrawProcessor) {
            return $withdrawProcessor->verifyWithdraw($transaction, $withdrawMethodDetails);
        }

        return false;
    }

    public function formatAccountDetails($userAccount)
    {
        $withdrawMethod = $this->getWithdrawMethod(data_get($userAccount, 'slug'));
        $processor = $this->getWithdrawProcessor($withdrawMethod);
        return $processor->getAccountDetails($userAccount);
    }

    public function formatAccountName($userAccount)
    {
        $withdrawMethod = $this->getWithdrawMethod(data_get($userAccount, 'slug'));
        $processor = $this->getWithdrawProcessor($withdrawMethod);
        return $processor->getAccountName($userAccount);
    }

    public function getCurrency($userAccount)
    {
        $withdrawMethod = $this->getWithdrawMethod(data_get($userAccount, 'slug'));
        $processor = $this->getWithdrawProcessor($withdrawMethod);

        return $processor->getCurrency($userAccount);
    }

    public function getMethod($userAccount, $output='name')
    {
        $withdrawMethod = $this->getWithdrawMethod(data_get($userAccount, 'slug'));
        $processor = $this->getWithdrawProcessor($withdrawMethod);

        return $processor->getMethod($output);
    }

    public function getWithdrawDetailsView($transaction)
    {
        $withdrawMethodDetails = $this->getWithdrawMethod($transaction->tnx_method);
        $withdrawProcessor = $this->getWithdrawProcessor($withdrawMethodDetails);

        if ($withdrawProcessor && $withdrawProcessor->isEnable()) {
            return $withdrawProcessor->getDetailsView($transaction, $withdrawMethodDetails);
        } else {
            return view('misc.view-transaction', compact('transaction'));
        }
    }
}
