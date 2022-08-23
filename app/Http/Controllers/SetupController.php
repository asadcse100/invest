<?php


namespace App\Http\Controllers;


use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class SetupController extends Controller
{
    public function runMigration()
    {
        try {
            Artisan::call('migrate', [
                '--force' => true,
            ]);

            return redirect()->route('auth.login');
        } catch (\Exception $e) {
            Log::error($e->getMessage(), $e->getTrace());
            echo 'There has been error running migration please check your database connection and other pre-requisites';
        }
    }

    public function runSeed()
    {
        try {
            Artisan::call('db:seed --class=DatabaseSeeder');

            return redirect()->route('auth.login');
        } catch (QueryException $e) {
            Log::error($e->getMessage(), $e->getTrace());
            echo "Please run database migration properly  before seeding !";
        }
    }
}
