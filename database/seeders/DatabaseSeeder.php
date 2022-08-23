<?php

namespace Database\Seeders;

use App\Updates\UpdateManager;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    private $updateManager;

    public function __construct(UpdateManager $update)
    {
        $this->updateManager = $update;
    }

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SettingSeeder::class);
        $this->call(InvestSettings::class);
        $this->call(PagesSeeder::class);
        $this->call(InvestDefaultSchemes::class);
        $this->call(InvestEmailTemplates::class);
        $this->call(EmailTemplatesSeeder::class);
        $this->call(PaymentMethodSeeder::class);
        $this->call(WithdrawMethodSeeder::class);
        
        $this->updateManager->update();
    }
}
