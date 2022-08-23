<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\WrapInTransaction;
use App\Updates\UpdateManager;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UpdateManagerController extends Controller
{
    use WrapInTransaction;
    /**
     * @var UpdateManager
     */
    private $updateManager;

    public function __construct(UpdateManager $updateManager)
    {
        $this->updateManager = $updateManager;
    }

    public function index(Request $request)
    {
        if (!$this->updateManager->hsaPendingMigration() && !$this->updateManager->isUpdateAvailable()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.settings.update.installer', [
            'manager' => $this->updateManager,
        ]);
    }

    public function install()
    {
        try {
            if (!$this->updateManager->hsaPendingMigration() && !$this->updateManager->isUpdateAvailable()) {
                throw ValidationException::withMessages(['update' =>  __("No pending update or migration found.")]);
            }

            return $this->wrapInTransaction(function(){
                $this->updateManager->update();

                return response()->json([ 'msg' => __("The updates has been successfully installed."), 'timeout' => 900, 'url' => route('admin.dashboard')] );
            });
        } catch (\Exception $e) {
            save_msg_log($e->getMessage(), 'notice');
            return response()->json(['type' => 'warning', 'msg' => __("An error occurred. Please try again.")]);
        }
    }
}
