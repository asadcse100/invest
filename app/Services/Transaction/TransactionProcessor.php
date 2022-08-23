<?php


namespace App\Services\Transaction;


use App\Models\PaymentMethod;
use App\Interfaces\Payable;
use Illuminate\Validation\ValidationException;

class TransactionProcessor
{
    /**
     * @param $paymentMethod
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    private function getPaymentMethod($paymentMethod)
    {
        return PaymentMethod::where('slug', $paymentMethod)->first();
    }

    /**
     * @param $paymentMethod
     * @return Payable|false
     * @throws \Exception
     * @version 1.0.0
     * @since 1.0
     */
    public function getPaymentProcessor($paymentMethod)
    {
        $paymentProcessor = data_get($paymentMethod, 'module_config.processor');

        if (!blank($paymentProcessor) && class_exists($paymentProcessor)) {
            $paymentProcessor =  new $paymentProcessor();
            if ($paymentProcessor instanceof Payable) {
                return $paymentProcessor;
            } else {
                throw new \Exception(__("Payment processor must be an instance of Payable"));
            }
        }

        return false;
    }


    /**
     * @param $transaction
     * @param $paymentMethod
     * @return false
     * @throws \Exception
     * @version 1.0.0
     * @since 1.0
     */
    public function process($transaction, $paymentMethod)
    {
        $paymentMethodDetails = $this->getPaymentMethod($paymentMethod);
        $paymentProcessor = $this->getPaymentProcessor($paymentMethodDetails);

        if ($paymentProcessor) {
            return $paymentProcessor->makePayment($transaction, $paymentMethodDetails);
        }

        return false;
    }

    /**
     * @param $transaction
     * @param $paymentMethod
     * @return false
     * @throws \Exception
     * @version 1.0.0
     * @since 1.0
     */
    public function verify($transaction)
    {
        $paymentMethodDetails = $this->getPaymentMethod($transaction->method_slug);
        $paymentProcessor = $this->getPaymentProcessor($paymentMethodDetails);

        if ($paymentProcessor) {
            return $paymentProcessor->verifyPayment($transaction, $paymentMethodDetails);
        } else {
            throw ValidationException::withMessages(['processor' => __('Transaction Verification Failed !')]);
        }
    }

    public function getTransactionDetailsView($transaction)
    {
        $paymentMethodDetails = $this->getPaymentMethod($transaction->method_slug);
        $paymentProcessor = $this->getPaymentProcessor($paymentMethodDetails);

        if ($paymentProcessor && $paymentProcessor->isEnable()) {
            return $paymentProcessor->getDetailsView($transaction, $paymentMethodDetails);
        } else {
            return view('misc.view-transaction', compact('transaction'));
        }
    }
}
