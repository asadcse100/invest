<?php

namespace App\Services\Exports;

use App\Enums\AccountBalanceType;
use App\Models\User;
use App\Services\Exports\CsvExport;

class UserCsvExport
{
    private $csvExport;

    public function __construct() {
        $this->csvExport = new CsvExport;
    }

    public function download($exportType)
    {
        $csvExport = $this->csvExport;
        $csvExport->setFile("users_" . $exportType . ".csv");

        $file = $csvExport->getFile();
        $exportMethod = $exportType . 'Export';
        $users = User::with('user_metas')->orderBy('status')->lazy();
        $this->$exportMethod($users);
        $csvExport->download($file);
    }

    /**
     * @param $users
     * @version 1.0.0
     * @since 1.1.2
     */
    private function entireExport($users)
    {
        $csvExport = $this->csvExport;
        $fields = [
            'ID',
            'Name',
            'Email',
            'Mobile',
            'Balance',
            'Gender',
            'DOB',
            'Country',
            'RegMethod',
            'Status',
            'Join'
        ];   
        $csvExport->setRow($fields);

        $users->each(function ($user) use ($csvExport) {
            $meta = $user->user_metas->pluck('meta_value', 'meta_key')->toArray();
            $row = [
                the_uid($user->id),
                $user->name,
                $user->email,
                data_get($meta, 'profile_phone', ''),
                user_balance(AccountBalanceType::MAIN, $user->id),
                data_get($meta, 'profile_gender', ''),
                data_get($meta, 'profile_dob', ''),
                data_get($meta, 'profile_country', ''),
                data_get($meta, 'registration_method', ''),
                $user->status,
                $user->created_at,
            ];
            $csvExport->setRow($row);
        });      
    }
    
    /**
     * @param $users
     * @version 1.0.0
     * @since 1.1.2
     */
    private function minimumExport($users)
    {
        $csvExport = $this->csvExport;
        $fields = [
            'ID',
            'Name',
            'Email',
            'Mobile',
            'Country',
            'Balance',
            'Status'
        ];
        $csvExport->setRow($fields);

        $users->each(function ($user) use ($csvExport) {
            $meta = $user->user_metas->pluck('meta_value', 'meta_key')->toArray();
            $row = [
                the_uid($user->id),
                $user->name,
                $user->email,
                data_get($meta, 'profile_phone', ''),
                data_get($meta, 'profile_country', ''),
                user_balance(AccountBalanceType::MAIN, $user->id),
                $user->status
            ];
            $csvExport->setRow($row);
        });
    }
    
    /**
     * @param $users
     * @version 1.0.0
     * @since 1.1.2
     */
    private function compactExport($users)
    {
        $csvExport = $this->csvExport;
        $fields = [
            'ID',
            'Email',
            'Balance',
            'Status'
        ];
        $csvExport->setRow($fields);

        $users->each(function ($user) use ($csvExport) {
            $row = [
                the_uid($user->id),
                $user->email,
                user_balance(AccountBalanceType::MAIN, $user->id),
                $user->status
            ];
            $csvExport->setRow($row);
        });
    }
}