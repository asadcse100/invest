<?php

namespace App\Http\View\Composers;

use App\Updates\UpdateManager;
use Illuminate\View\View;

class AdminWarningComposer
{
    /**
     * @var UpdateManager
     */
    private $updateManager;

    public function __construct(UpdateManager $updateManager)
    {

        $this->updateManager = $updateManager;
    }

    public function compose(View $view)
    {
        $view->with([
            'updateManager' => $this->updateManager
        ]);
    }
}