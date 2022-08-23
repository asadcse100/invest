<?php

namespace App\Console\Commands;

use App\Updates\UpdateManager;
use Illuminate\Console\Command;

class InstallUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'investorm:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update application to latest version';
    /**
     * @var UpdateManager
     */
    private $updateManager;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UpdateManager $updateManager)
    {
        parent::__construct();
        $this->updateManager = $updateManager;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Application updating.");

        try {
            $this->updateManager->update();
        } catch (\Exception $e) {
            save_error_log($e, $e->getTrace());
            $this->error("Update process failed.");
        }

        $this->info("Application updated successfully.");
    }
}
