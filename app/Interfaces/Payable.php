<?php


namespace App\Interfaces;


interface Payable
{
    public function makePayment($transaction, $paymentMethodDetails);

    public function verifyPayment($transaction, $paymentMethodDetails);

    public function isActive();

    public function isEnable();

    public function hasConfig();

    public function getDetailsView($transaction, $paymentMethodDetails);

    public static function getShortcutInfo($transaction);
}
