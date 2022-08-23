<?php

use NioModules\WdPaypal\WdPaypalModule;

return [
    WdPaypalModule::SLUG => [
        'name' => __('PayPal'),
        'slug' => WdPaypalModule::SLUG,
        'method' => WdPaypalModule::METHOD,
        'account' => __('PayPal Account'),
        'icon' => 'ni-paypal-alt',
        'full_icon' => 'ni-sign-paypal-full',
        'is_online' => false,
        'processor_type' => 'withdraw',
        'processor' => WdPaypalModule::class,
        'rounded' => 2,
        'supported_currency' => [
            'USD', 'EUR', 'GBP', 'CAD', 'AUD', 'RUB', 'BRL', 'MXN'
        ],
        'system' => [
            'kind' => 'Withdraw',
            'info' => 'Gateway / Offline',
            'type' => WdPaypalModule::MOD_TYPES,
            'version' => WdPaypalModule::VERSION,
            'update' => WdPaypalModule::LAST_UPDATE,
            'description' => 'Manage withdraw funds manually using paypal.',
            'addons' => false,
        ]
    ],
];
