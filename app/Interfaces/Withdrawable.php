<?php


namespace App\Interfaces;


interface Withdrawable
{
    public function makeWithdraw($transaction, $withdrawMethodDetails);

    public function verifyWithdraw($transaction, $withdrawMethodDetails);

    public function isActive();

    public function hasConfig();

    public function getMethod();

    public function getAccountDetails($accountConfig);

    public function getAccountName($account);

    public function getCurrency($account);

    public function getDetailsView($transaction, $withdrawMethodDetails);

    public static function getShortcutInfo($transaction);
}
