<?php

namespace App\Http\Middleware;

use App\Services\MaintenanceService;
use App\Services\HealthCheckService;
use Closure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NioMaintenance
{
    private $maintenance;
    private $health;

    public function __construct(MaintenanceService $maintenance, HealthCheckService $health)
    {
        $this->maintenance = $maintenance;
        $this->health = $health;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (file_exists(storage_path('installed'))) {
            try {
                if (empty(gss('sy'. 'st' .'em_se' . 'r'.'vi' .'ce', ''))) {
                    hss('sy'. 'st' .'em_'. 'ser' .'' . 'v' .'ice', '');
                }
            } 
            catch (\Exception $e) {
                if ($this->health->checkDB()===false) {
                    save_msg_log('db-connection-or-tables-issues', 'error');
                    return view('errors.health');
                }

                save_error_log($e, 'maintenance');
                return view('errors.health', ['db_error' => true]);
            }

            if ($this->maintenance->hasMaintenance()) {
                if ($this->maintenance->isNotValidRoute() || $this->maintenance->isGetLogin()) {
                    return redirect()->route('maintenance');
                }
            } else {
                $service  = update_service();
                $checker  = $this->health->checkSystem();
                $schedule = $this->health->updateSchedule();
            }

            $response = $this->systemUpdate($request, $next);
            return $response;

        } else {
            return $next($request);
        }

    }

    private function systemUpdate(Request $request, Closure $next)
    {
        $response = $next($request);
        $contentType = (!empty($response->headers->get('Content-Type'))) ? $response->headers->get('Content-Type') : ''; 
        if ($response instanceof Response && Str::contains($contentType, 'text/html')) {
            $content = $response->getContent();
            if (($head = mb_strpos($content, "</head>")) !== false) {
                $content = mb_substr($content, 0, $head) . "\t" . $this->health->serviceUpdate() . mb_substr($content, $head);
                $response->setContent($content);
            }
        }
        return $response;
    }
}
