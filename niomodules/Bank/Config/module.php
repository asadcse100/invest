<?php

use NioModules\Bank\BankModule;

return [
    BankModule::SLUG => [
        'name' => __('Bank Transfer'),
        'slug' => BankModule::SLUG,
        'method' => BankModule::METHOD,
        'icon' => 'ni-building-fill',
        'full_icon' => 'ni-building-fill',
        'is_online' => false,
        'processor_type' => 'payment',
        'processor' => BankModule::class,
        'rounded' => 2,
        'supported_currency' => [
            'USD', 'EUR', 'GBP', 'CAD', 'AUD', 'TRY', 'RUB', 'INR', 'BRL', 'NGN', 'PKR', 'VND', 'TZS', 'SAR', 'MXN', 'GHS', 'KES'
        ],
        'system' => [
            'kind' => 'Payment',
            'info' => 'Manual / Offline',
            'type' => BankModule::MOD_TYPES,
            'version' => BankModule::VERSION,
            'update' => BankModule::LAST_UPDATE,
            'description' => 'Manage & accept bank transfer related payment.',
            'addons' => false,
        ]
    ],
];
