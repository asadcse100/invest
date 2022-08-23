<?php

use NioModules\WdBank\WdBankModule;

return [
    WdBankModule::SLUG => [
        'name' => __('Bank Transfer'),
        'slug' => WdBankModule::SLUG,
        'method' => WdBankModule::METHOD,
        'account' => __('Bank Account'),
        'icon' => 'ni-building-fill',
        'full_icon' => 'ni-building-fill',
        'is_online' => false,
        'processor_type' => 'withdraw',
        'processor' => WdBankModule::class,
        'rounded' => 2,
        'supported_currency' => [
            'USD', 'EUR', 'GBP', 'CAD', 'AUD', 'TRY', 'RUB', 'INR', 'BRL', 'NGN', 'PKR', 'VND', 'MXN', 'GHS', 'KES', 'TZS', 'SAR'
        ],
        'system' => [
            'kind' => 'Withdraw',
            'info' => 'Manual / Offline',
            'type' => WdBankModule::MOD_TYPES,
            'version' => WdBankModule::VERSION,
            'update' => WdBankModule::LAST_UPDATE,
            'description' => 'Manage withdraw funds manually via bank transfer.',
            'addons' => false,
        ]
    ],
];
