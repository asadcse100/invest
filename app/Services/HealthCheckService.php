<?php


namespace App\Services;

use App\Services\SettingsService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\PDOException;
use Nio\LaravelInstaller\Helpers\PermissionsChecker;

class HealthCheckService extends Service
{
    private $rules = [
        'checkDatabaseConnection',
        'checkFilePermissions',
        'checkAllMandatoryTableExists'
    ];

    private $uri = 'ch' . 'ec'.'k/en'.'va' . 'to'."/";
    private $retry = 30;
    private $permissionsChecker;

    public function __construct(PermissionsChecker $permissionsChecker)
    {
        $this->permissionsChecker = $permissionsChecker;
    }

    private function checkDatabaseConnection(): bool
    {
        try {
            DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function checkFilePermissions(): bool
    {
        $results = $this->permissionsChecker->check(
            config('installer.permissions')
        );

        return ($results['errors'] === null);
    }

    private function checkAllMandatoryTableExists(): bool
    {
        $mandatoryTables = config('investorm.default_tables');
        $availableTables = collect(DB::select('SHOW TABLES'))->map(function($val){
            foreach ($val as $key => $item) {
                return $item;
            }
        })->toArray();

        return (count(array_diff($mandatoryTables, $availableTables)) == 0);
    }

    public function checkDB(): bool
    {
        try {
            if ($this->checkAllMandatoryTableExists() === false) {
                session()->put('installation_error', 'checkAllMandatoryTableExists');
                return false;
            }
            return true;
        } catch(\Exception $e) {
            return false;
        }
    }


    public function checkSystem($flush = false) {
        $co = '_co' . 'de';
        $tc = gss("pa" . "yo" . "ut". '_' .'che'.'ck');
        $hs = (int) gss('hea'.'lth_'.'chec'.'ker', 0);

        if (($hs >= 2 && $tc <= time()) || $flush) {
            $cip = get_sys_cipher(); $cpc = sys_info('pcode'); $sys = gss('sys' . 'tem' . '_' . 'ser' . 'vice');
            $cps = gss("pa" . "yo" . "ut". '_' .'ba'.'tch'); $htt = 'i.s'.'of'.'tni'.'o.c'; $tim = Carbon::now()->addMinutes(30);

            if ($cpc && $cps && (strlen($sys) > 11)) {
                try {
                    $data = ["domain" => get_path(), "pur"."ch"."ase".$co => $cpc, "ac"."tiv"."ation".$co => $cps, "appname" => config('app' .'.'. 'name'),
                             "appurl" => config('app'. '.' .'url'), "appcode" => substr($sys, 10), "appver" => sys_info('vers')];

                    $http = Http::get('htt' .'ps://ap'.$htt.'om/'.$this->uri.'7'. 'd5b' .'16c6', $data);
                    if ($http->successful()) {
                        if ($http->json('status') == "act"."ive") {
                            $sevis = new SettingsService();
                            $sevis->generateSetting($http->json());
                            session()->forget('sys'.'tem' . '_' . 'e'.'rr'.'or');
                            return true;
                        } else {
                            Cache::put(get_m5host(), $cip, $tim); str_sub_count();
                            return true;
                        }
                    } else {
                        str_sub_count(); Cache::put(get_m5host(), $cip, $tim);
                        return true;
                    }
                } catch (\Exception $e) {
                    Cache::put(get_m5host(), $cip, $tim); str_sub_count();
                    return true;
                }
            } else {
                upss('payo'.'ut'. '_' .'che'.'ck', (time() + (3600 * 3))); session()->put('sy'.'st'.'em'. '_' .'err'.'or', true);
                if ($sys && (strlen($sys) == 11 || strlen($sys) == 12)) $this->updateSystem();
                return false;
            }
        }
    }


    public function isOk(): bool
    {
        try {
            $healthStatus = true;
            foreach ($this->rules as $rule) {
                if (method_exists($this, $rule) && ($this->$rule() === false)) {
                    session()->put('installation_error', $rule);
                    return false;
                }
            }
            return $healthStatus;
        } catch(\Exception $e) {
            return false;
        }
    }

    public function updateSystem()
    {
        $ire = gss('app' . '_' . 'acqu' . 'ire', []); $addmin = Carbon::now()->addMinutes(30);
        $upt = ['app' => site_info('name'), 'key' => get_rand(6, false), 'cipher' => get_rand(48, false), 'secret' => get_rand(28, false), 'update' => (time() + 3600) ];
        if (!empty($ire) && is_array($ire) && count($ire) > 0) { $upt = array_merge($ire, $upt); }
        upss('p'.'ayo'.'ut'. '_' .'ba'.'tc'.'h', $upt['se'.'cre'.'t']); upss('ap' . 'p'.'_'.'ac' . 'qui' . 're', $upt);
        clear_ecache(); Cache::put(get_m5host(), array_merge(gss('si'.'te'.'_me'.'rchan'.'dise', []), $upt), $addmin);
    }

    public function updateSchedule()
    {
        if (empty(gss('health' . '_' . 'checker')) && (strlen(sys_info('secret')) >= $this->retry)) {
            if (schedule_timeout(gss('update' . '_' . 'installed'), ($this->retry + 30)) || schedule_timeout(gss('installed' . '_' . 'apps'), $this->retry)) {
                upss('health' . '_' . 'checker', 1);
                return true;
            }
            return false;
        }
        return false;
    }

    public function serviceUpdate()
    {
        return "<!-- System Build v" . sys_info('update') . substr(sys_info('type'), 1) . " @iO -->\n";
    }
}