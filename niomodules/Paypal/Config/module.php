<?php

use NioModules\Paypal\PaypalModule;

return [
    PaypalModule::SLUG => [
        'name' => __('PayPal'),
        'slug' => PaypalModule::SLUG,
        'method' => PaypalModule::METHOD,
        'icon' => 'ni-paypal-alt',
        'full_icon' => 'ni-sign-paypal-full',
        'is_online' => true,
        'processor_type' => 'payment',
        'processor' => PaypalModule::class,
        'rounded' => 2,
        'supported_currency' => [
            'USD', 'EUR', 'GBP', 'CAD', 'AUD', 'RUB', 'BRL', 'MXN'
        ],
        'system' => [
            'kind' => 'Payment',
            'info' => 'Gateway / Online',
            'type' => PaypalModule::MOD_TYPES,
            'version' => PaypalModule::VERSION,
            'update' => PaypalModule::LAST_UPDATE,
            'description' => 'Accept fiat currency related payments via paypal.',
            'addons' => false,
        ]
    ],
];
