<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class WithdrawMethodSeeder extends Seeder
{
    public function run()
    {
        $paymentMethods = [
            0 => [
                'slug' => 'paypal',
                'name' => 'Paypal',
                'desc' => 'Paypal Payment Method',
                'min_amount' => 5,
                'config' => [],
                'fees' => [
                    'currency' => 'USD',
                    'amount' => 5.00,
                    'type' => 'flat'
                ],
                'currencies' => ['USD', 'BDT'],
                'countries' => ['USA', 'BD'],
                'status' => \App\Enums\PaymentMethodStatus::INACTIVE,
            ],
            2 => [
                'slug' => 'bank-transfer',
                'name' => 'Bank Transfer',
                'desc' => 'Bank Transfer Method',
                'min_amount' => 10,
                'config' => [],
                'fees' => [
                    'currency' => 'USD',
                    'amount' => 10.00,
                    'type' => 'flat',
                ],
                'currencies' => ['USD', 'BDT'],
                'countries' => ['USA', 'BD'],
                'status' => \App\Enums\PaymentMethodStatus::INACTIVE,
            ],
            3 => [
                'slug' => 'crypto-wallet',
                'name' => 'Crypto Wallet',
                'desc' => 'Crypto Wallet Payment Method',
                'min_amount' => 10,
                'config' => [],
                'fees' => [
                    'currency' => 'USD',
                    'amount' => 10.00,
                    'type' => 'flat',
                ],
                'currencies' => ['USD', 'BDT'],
                'countries' => ['USA', 'BD'],
                'status' => \App\Enums\PaymentMethodStatus::INACTIVE,
            ],
        ];


        foreach ($paymentMethods as $method) {
            $paymentMethod = PaymentMethod::where('slug', $method['slug'])->first();
            if (blank($paymentMethod)) {
                $paymentMethod = new PaymentMethod();
                $paymentMethod->fill($method);
                $paymentMethod->save();
            }
        }
    }
}
