<?php


namespace App\Services;


use App\Models\UserActivity;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Jenssegers\Agent\Agent;

class ActivityLogger extends Service
{
    private $agent;
    public function __construct(Agent $agent)
    {
        $this->agent = $agent;
    }

    /**
     * @return bool
     * @version 1.0.0
     * @since 1.0
     */
    private function activityLogEnabled()
    {
        $userMetas = auth()->user()->user_metas->pluck('meta_value', 'meta_key');
        return Arr::get($userMetas, 'setting_activity_log') == 'on';
    }

    /**
     * @param null $message
     * @version 1.0.0
     * @since 1.0
     */
    public function saveActivityLog($message = null)
    {
        if (!$this->activityLogEnabled()) {
            return;
        }

        $userActivities = new UserActivity();
        $userActivities->fill([
            "user_id" => auth()->user()->id,
            "session" => Carbon::now(),
            "ip" => request()->ip(),
            "meta" => $message,
            "browser" => $this->agent->browser(),
            "device" => $this->agent->device(),
            "platform" => $this->agent->platform(),
            "version" => [
                "browser" => $this->agent->version($this->agent->browser()),
                "platform" => $this->agent->version($this->agent->platform()),
            ],
        ]);

        $userActivities->save();
    }
}
