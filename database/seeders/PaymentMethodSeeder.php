<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use App\Enums\PaymentMethodStatus;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run()
    {
        $paymentMethods = [
            0 => [
                'slug' => 'paypal',
                'name' => 'Paypal',
                'desc' => 'Pay securely with your PayPal account.',
                'min_amount' => 5,
                'config' => [],
                'fees' => [
                    'flat' => 0,
                    'percent' => 0
                ],
                'currencies' => ['USD'],
                'countries' => [],
                'status' => PaymentMethodStatus::INACTIVE,
            ],
            2 => [
                'slug' => 'bank-transfer',
                'name' => 'Bank Transfer',
                'desc' => 'Make payment directly into our bank account.',
                'min_amount' => 100,
                'config' => [],
                'fees' => [
                    'flat' => 0,
                    'percent' => 0
                ],
                'currencies' => ['USD'],
                'countries' => [],
                'status' => PaymentMethodStatus::INACTIVE,
            ],
            3 => [
                'slug' => 'crypto-wallet',
                'name' => 'Crypto Wallet',
                'desc' => 'Send your payment direct to our wallet.',
                'min_amount' => 0.1,
                'config' => [],
                'fees' => [
                    'flat' => 0,
                    'percent' => 0
                ],
                'currencies' => ['BTC'],
                'countries' => [],
                'status' => PaymentMethodStatus::INACTIVE,
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
