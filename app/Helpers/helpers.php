<?php

use App\Enums\ExchangeRateUpdateType;
use App\Enums\PaymentProcessorType;
use App\Enums\PaymentMethodStatus;
use App\Enums\TransactionCalcType;
use App\Enums\AccountBalanceType;
use App\Enums\TransactionStatus;
use App\Enums\InvestmentStatus;
use App\Enums\TransactionType;
use App\Enums\UserRoles;
use App\Enums\UserStatus;

use App\Models\User;
use App\Models\Page;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\ReferralCode;
use App\Models\PaymentMethod;
use App\Models\Setting;
use App\Models\IvInvest;
use App\Models\IvProfit;

use App\Helpers\NioHash;
use App\Helpers\MsgState;
use App\Jobs\ProcessEmail;
use App\Models\Language;
use App\Models\UserMeta;
use App\Models\WithdrawMethod;
use Carbon\Carbon;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cookie;

/**
 * Custom Helper Functions
 *
 *
 * @package Investorm
 * @author Softnio
 * @version 1.0.0
 * @since 1.0
 * @return void
 */

if (!function_exists('is_json')) {
    /**
     * check json value
     * @param $string, $decoded
     * @version 1.0.0
     * @since 1.0
     */
    function is_json($string, $decoded = false)
    {
        if (is_array($string)) {
            return false;
        }
        json_decode($string);
        $check = (json_last_error() == JSON_ERROR_NONE);

        if ($decoded && $check) {
            return json_decode($string);
        }

        return $check;
    }
}


if (!function_exists('is_force_https')) {
    /**
     * Check if force to https form configure.
     * @version 1.0.0
     * @since 1.0
     */
    function is_force_https()
    {
        if (config('app.force_https')) {
            return true;
        }

        return false;
    }
}

if (!function_exists('random_hash')) {
    /**
     * Genarate a token with sha1 encoding
     * @param $str
     * @version 1.0.0
     * @since 1.0
     */
    function random_hash($str)
    {
        $key = (!empty($str)) ? $str : Str::random(24);
        return sha1($key . Str::random(10));
    }
}

if (!function_exists('hash_id')) {
    /**
     * @param $userID
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function hash_id($userID, $type = false)
    {
        $hash = ($type) ? $userID : the_uid($userID);
        return cipher($hash);
    }
}

if (!function_exists('get_rand')) {
    /**
     * @param $len
     * @version 1.0.0
     * @since 1.0
     */
    function get_rand($len = 12, $uc = true)
    {
        $rand = Str::random($len);
        return ($uc) ? strtoupper($rand) : $rand;
    }
}

if (!function_exists('has_route')) {
    /**
     * check if route exist
     * @param $name
     * @version 1.0.0
     * @since 1.0
     */
    function has_route($name)
    {
        return Route::has($name);
    }
}

if (!function_exists('is_route')) {
    /**
     * check current route
     * @param $name
     * @version 1.0.0
     * @since 1.0
     */
    function is_route($name)
    {
        return request()->routeIs($name);
    }
}

if (!function_exists('activity_log')) {
    /**
     * saves activity log
     * @param $message
     * @version 1.0.0
     * @since 1.0
     */
    function activity_log($message = null)
    {
        app(\App\Services\ActivityLogger::class)->saveActivityLog($message);
    }
}

if (!function_exists('last_word')) {
    /**
     * @param $str
     * @return mixed|string
     * @version 1.0.0
     * @since 1.0
     */
    function last_word($str)
    {
        $words = explode(' ', $str);
        return array_pop($words);
    }
}

if (!function_exists('random_color')) {
    /**
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function random_color($sufix = '')
    {
        $colors = [
            'primary', 'secondary', 'gray', 'success', 'danger',  'warning',  'info',
            'blue', 'azure', 'indigo', 'purple', 'pink', 'orange', 'teal'
        ];
        $key = array_rand($colors, 1);
        $color = $colors[$key];
        return ($sufix) ? $color . '-' . $sufix : $color;
    }
}

if (!function_exists('the_data')) {
    /**
     * @param $array
     * @param $array
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function the_data($array = null, $key = null, $default = null)
    {
        $data = data_get($array, $key, $default);

        return ($data) ? $data : $default;
    }
}

if (!function_exists('first_word')) {
    /**
     * @param $str
     * @return mixed|string
     * @version 1.0.0
     * @since 1.0
     */
    function first_word($str)
    {
        $words = explode(' ', $str);
        return $words[0] ?? '';
    }
}

if (!function_exists("str_sub_count")) {
    /**
     * @param $str
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function str_sub_count($str = null, $len = 11, $sub = 1)
    {
        $str = ($str) ? $str : gss('sys'.'tem_se'.'rvi'.'ce');
        $cnt = strlen($str);
        $sub = ($sub > 0) ? $sub : 1;

        if ($cnt > $len) {
            $sta = substr($str, 0, -$sub);
            upss('sy'.'ste'.'m_se'.'rv'.'ice', $sta);
            upss('pa'.'you'.'t_ch'.'eck', (time() + 3600));
            return strlen($sta);
        }

        return $cnt;
    }
}

if (!function_exists('str_compact')) {
    /**
     * @param $str
     * @param $sep
     * @param $length
     * @return mixed|string
     * @version 1.0.0
     * @since 1.0
     */
    function str_compact($str, $sep = '...', $length = 4, $end = 0)
    {
        $length = ($length) ? $length : 1;
        $len = strlen($str);
        $max = floor($len / 2);
        $min = (1 + ($length * 2) + ($end * 2));

        if ($len <= 5) {
            return $str;
        }
        $sub = ($len >= $min) ? $length : (((min($length, $max) * 2) <= $len) ? min($min, $max) : min($length, $max));
        $sub = ($length < $sub) ? $length : $sub;
        $sub_end = ($sub > $end) ? $sub : $end;
        return substr($str, 0, $sub) . $sep . substr($str, -$sub_end);
    }
}

if (!function_exists('str_end')) {
    /**
     * @param $str
     * @param $prefix
     * @param $sep
     * @param $length
     * @param $dot
     * @return mixed|string
     * @version 1.0.0
     * @since 1.0
     */
    function str_end($str, $prefix = '', $sep = '-', $length = 4, $dot = false)
    {
        $length = ($length) ? $length : 2;
        $regx = "[^A-Za-z0-9" . (($dot) ? "." : "") . "]";
        $str = preg_replace('/' . $regx . '/', '', $str);
        $start = '';

        if ($prefix) {
            $start = ($prefix === true) ? $sep : $prefix . $sep;
        }

        return $start . substr($str, -$length, $length);
    }
}


if (!function_exists("str_con")) {
    /**
     * @param $str
     * @param $match
     * @return mixed
     */
    function str_con($str = null, $match = null)
    {
        $str = ($str) ? cipher($str) : cipher(get_dkey());
        $match = ($match) ? $match : get_etoken('secret');

        return ($match && $str) ? Str::contains($match, $str) : false;
    }
}


if (!function_exists("str_dv2")) {
    /**
     * @param $str
     * @param $out
     * @return mixed
     */
    function str_dv2($str, $out = 2)
    {
        if (empty($str)) {
            return '';
        }

        $len = strlen($str);
        $num = $len/2;
        return ($out == 2) ? substr($str, 0, $num) : substr($str, -$num, $num);
    }
}

if (!function_exists('str_char')) {
    /**
     * @param $str
     * @return string
     */
    function str_char($str) {
       $string = str_replace(' ', '-', $str);
       return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
    }
}


if (!function_exists('str_protect')) {
    /**
     * @param $string
     * @param int | $len
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function str_protect($string, $len=3)
    {
        return is_demo_user() ? substr($string, 0, $len).'...'.substr($string, -$len) : $string;
    }
}

if (!function_exists('the_tnx')) {
    /**
     * @param $tnxID
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function the_tnx($tnxID, $type = 'tnx')
    {
        $prefix = ($type == 'ivx') ? 'ivx_prefix' : 'tnx_prefix';
        return config('investorm.' . $prefix) . $tnxID;
    }
}

if (!function_exists('get_tnx')) {
    /**
     * @param $tnx
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function get_tnx($tnx, $type = 'tnx')
    {
        $prefix = ($type == 'ivx') ? 'ivx_prefix' : 'tnx_prefix';
        return str_replace(Str::lower(config('investorm.' . $prefix)), '', Str::lower($tnx));
    }
}

if (!function_exists('the_inv')) {
    /**
     * @param $tnxID
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function the_inv($InvID)
    {
        return config('investorm.inv_prefix') . '-' . $InvID;
    }
}

if (!function_exists('get_inv')) {
    /**
     * @param $tnx
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function get_inv($inv)
    {
        return str_replace(Str::lower(config('investorm.inv_prefix')) . '-', '', Str::lower($inv));
    }
}

if (!function_exists('the_uid')) {
    /**
     * @param $userId
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function the_uid($userID)
    {
        return config('investorm.uid_prefix') . str_pad($userID, 5, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('get_uid')) {
    /**
     * @param $userId
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function get_uid($userID)
    {
        $uid = str_replace(Str::lower(config('investorm.uid_prefix')), '', Str::lower($userID));
        return (int) ltrim($uid, '0');
    }
}

if (!function_exists('retrieve_ref_code')) {
    /**
     * @param $userID
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function retrieve_ref_code($userID)
    {
        $refer = ReferralCode::where('user_id', $userID)->first();

        return (!blank($refer)) ? $refer->code : false;
    }
}

if (!function_exists('retrieve_ref_user')) {
    /**
     * @param $hash
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function retrieve_ref_user($hash)
    {
        $refer = ReferralCode::where('code', $hash)->first();

        return (!blank($refer)) ? $refer->user_id : false;
    }
}


if (!function_exists('fees_calc')) {
    /**
     * @param $method
     * @param $amount
     * @param $currency
     * @param $inclusive
     * @return mixed
     * @version 1.0.0
     * @since 1.1.4
     */
    function fees_calc($method, $amount, $currency, $inclusive=false)
    {
        $result = ['total' => 0];
        $type = data_get($method, 'module');
        $service = ($type == 'payment') ? 'deposit' : $type;
        if (module_exist('NioExtend', 'addon') && has_service($service, hash_id('fee'.'4dw', true))) {
            $fees = app()->get('nioextend')->fees;
            if (!blank($fees)) {
                $result = $fees->setMethod($method)
                    ->setAmount($amount)
                    ->setCurrency($currency)
                    ->setInclusive($inclusive)
                    ->calculateFee();
            }
        }
        return $result;
    }
}

if (!function_exists('get_ref_code')) {
    /**
     * @param $user
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function get_ref_code($user)
    {
        if (!empty($user->referral_code)) {
            return $user->referral_code;
        } else {
            $userId = is_object($user) ? $user->id : $user;
            $refer = ReferralCode::where('user_id', $userId)->first();

            if (blank($refer)) {
                $refer = ReferralCode::create([
                            'user_id' => $userId,
                            'code' => hash_id($userId),
                            'type' => 0,
                        ]);
            }
            return $refer->code;
        }
    }
}

if (!function_exists('the_hash')) {
    /**
     * @param $data
     * @param $match
     * @return string|boolean
     * @version 1.0.0
     * @since 1.0
     */
    function the_hash($data, $match = null)
    {
        return NioHash::of($data, $match);
    }
}

if (!function_exists('get_hash')) {
    /**
     * @param $data
     * @return string|boolean
     * @version 1.0.0
     * @since 1.0
     */
    function get_hash($data)
    {
        return NioHash::toID($data);
    }
}


if (!function_exists('css_state')) {
    /**
     * @param $status
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function css_state($status, $prefix = '', $default = '', $type = 'user')
    {
        $statusClass = false;
        $prefix = ($prefix) ? $prefix . '-' : '';
        $default = ($default) ? ' ' . $prefix . 'gray' : '';

        if ($type == 'user') {
            $statusClass = [
                UserStatus::ACTIVE => $prefix . 'success',
                UserStatus::INACTIVE => $prefix . 'warning',
                UserStatus::SUSPEND => $prefix . 'danger',
                UserStatus::LOCKED => $prefix . 'info',
                UserStatus::DELETED => $prefix . 'gray',
            ];
        } elseif ($type == 'tnx') {
            $statusClass = [
                TransactionStatus::PENDING => $prefix . 'primary',
                TransactionStatus::ONHOLD => $prefix . 'info',
                TransactionStatus::CONFIRMED => $prefix . 'info',
                TransactionStatus::CANCELLED => $prefix . 'danger',
                TransactionStatus::FAILED => $prefix . 'warning',
                TransactionStatus::COMPLETED => $prefix . 'success',
            ];
        } elseif ($type == 'static') {
            $statusClass = [
                'pending' => $prefix . 'warning',
                'active' => $prefix . 'primary',
                'started' => $prefix . 'primary',
                'inactive' => $prefix . 'gray',
                'resubmit' => $prefix . 'warning',
                'completed' => $prefix . 'success',
                'verified' => $prefix . 'success',
                'cancelled' => $prefix . 'danger',
                'rejected' => $prefix . 'danger',
                'reject' => $prefix . 'danger',
                'approve' => $prefix . 'success',
                'complete' => $prefix . 'info',
            ];
        }

        return (isset($statusClass[$status]) && $statusClass[$status]) ? ' ' . $statusClass[$status] : $default;
    }
}

if (!function_exists('to_past')) {
    /**
     * @param $str
     * @return string
     * @version 1.0.0
     * @since 1.2.0
     */
    function to_past($str)
    {
        $wordMap = [
            'start' => 'started',
            'active' => 'actived',
            'complete' => 'completed',
            'approve' => 'approved',
            'resubmit' => 'resubmitted',
            'verify' => 'verified',
            'cancel' => 'cancelled',
            'reject' => 'rejected',
        ];
        return (isset($wordMap[$str])) ? $wordMap[$str] : $str;
    }
}

if (!function_exists('tnx_status_switch')) {
    /**
     * @param $status
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function tnx_status_switch($status)
    {
        $state = strtolower($status);

        $statusClass = [
            TransactionStatus::COMPLETED => 'paid',
            TransactionStatus::PENDING => 'due',
            TransactionStatus::ONHOLD => 'scheduled',
        ];

        return (isset($statusClass[$state]) && $statusClass[$state]) ? $statusClass[$state] : $status;
    }
}


if (!function_exists('css_state_tnx') && function_exists('css_state')) {
    /**
     * @param $status
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function css_state_tnx($status, $prefix = '', $default = '')
    {
        return css_state($status, $prefix, $default, 'tnx');
    }
}


if (!function_exists('the_state') && function_exists('css_state')) {
    /**
     * @param $status
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function the_state($status, $attr = [])
    {
        $prams = ['prefix' => '', 'default' => '', 'type' => 'static'];
        $prams = array_merge($prams, $attr);
        extract($prams);

        return css_state($status, $prefix, $default, $type);
    }
}


if (!function_exists('tnx_type_icon')) {
    /**
     * @param $tnx
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function tnx_type_icon($tnx, $iconset = 'tnx-type-icon', $theme = 'dim')
    {
        $type = $tnx->type;
        $icon_method = data_get($tnx, 'method_icon_class');

        $typeMap = [
            TransactionType::DEPOSIT => ['icon' => 'arrow-down-left', 'color' => 'success'],
            TransactionType::WITHDRAW => ['icon' => 'arrow-up-right', 'color' => 'warning'],
            TransactionType::BONUS => ['icon' => 'arrow-to-right', 'color' => 'success'],
            TransactionType::CHARGE => ['icon' => 'arrow-to-left', 'color' => 'danger'],
            TransactionType::INVESTMENT => ['icon' => 'exchange', 'color' => 'purple'],
            TransactionType::REFERRAL => ['icon' => 'percent', 'color' => 'info']
        ];

        $icon   = $typeMap[$type]['icon'] ?? 'shuffle';
        $icontp = ($iconset) ? 'nk-' . $iconset : '';
        $theme  = ($theme == 'dim') ? '-dim' : '';
        $color = $typeMap[$type]['color'] ?? 'secondary';

        $colors  = ($theme) ? 'bg-' . $color . $theme . ' text-' . $color : 'bg-' . $color . ' text-white';

        $output = '<span class="' . $icontp . ' ' . $colors . ' icon ni ni-' . $icon . '"></span>';
        $output = ($icon_method) ? $output . '<span class="' . $icontp . ' text-secondary icon ni ' . $icon_method . '"></span>' : $output;

        return $output;
    }
}


if (!function_exists('from_to_case')) {
    /**
     * @param $name
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function from_to_case($name)
    {
        return ucfirst(str_replace(['-', '_'], ' ', $name));
    }
}


if (!function_exists('calc_sign')) {
    /**
     * @param $name
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function calc_sign($calc)
    {
        $sign = '';

        if (TransactionCalcType::DEBIT == $calc) {
            $sign = '-';
        } elseif (TransactionCalcType::CREDIT == $calc) {
            $sign = '+';
        } elseif (TransactionCalcType::NONE == $calc) {
            $sign = '~';
        }

        return $sign;
    }
}

if (!function_exists('user_meta')) {
    /**
     * @param $metaKey
     * @param null $default
     * @param null $user
     * @return array|mixed
     * @version 1.0.0
     * @since 1.0
     */
    function user_meta($metaKey, $default = null, $user = null)
    {
        $user = (blank($user)) ? auth()->user() : $user;

        if (!blank($user)) {
            $userMetas = $user->user_metas->pluck('meta_value', 'meta_key');
            if (!blank($userMetas)) {
                return data_get($userMetas, $metaKey, $default);
            }
        }
        return ($default) ? $default : false;
    }
}

if (!function_exists('user_theme')) {
    /**
     * @version 1.0.0
     * @since 1.2.0
     */
    function user_theme()
    {
        return user_meta('profile_theme_mode', 'light');
    }
}

if (!function_exists('user_avatar')) {
    /**
     * @param $user
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function user_avatar($user, $size = '')
    {
        if (empty($user)) {
            return false;
        }
        $avatar_bg = isset($user->avatar_bg) && $user->avatar_bg ? $user->avatar_bg : 'primary';
        $avatar_size = ($size) ? ' ' . $size : '';

        return '<div class="user-avatar bg-' . $avatar_bg . $avatar_size . '"><span>' . Str::limit(Str::upper($user->name), 2, '') . '</span></div>';
    }
}

if (!function_exists('sanitize_input')) {
    /**
     * @param $str
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function sanitize_input($str)
    {
        $str = filter_var($str, FILTER_SANITIZE_STRING);
        $str = filter_var($str, FILTER_SANITIZE_SPECIAL_CHARS);
        return $str;
    }
}


if (!function_exists('is_demo')) {
    /**
     * @param $string
     * @return bool
     * @version 1.0.0
     * @since 1.0
     */
    function is_demo($name=null)
    {
        $demo = env('DEMO_MODE', false);

        if (empty($name)) {
            return ($demo) ? true : false;
        }

        if (in_array($name, ['private', 'live'])) {
            return ($demo===$name) ? true : false;
        }

        return false;
    }
}


if (!function_exists('is_live')) {
    /**
     * @param $string
     * @return bool
     * @version 1.0.0
     * @since 1.0
     */
    function is_live($name=null)
    {
        return (env('APP_ENV') == 'production' && !is_demo()) ? true : false;
    }
}


if (!function_exists('is_demo_private')) {
    /**
     * @return bool
     * @version 1.0.0
     * @since 1.0
     */
    function is_demo_private()
    {
        return is_demo('private') ? true : false;
    }
}


if (!function_exists('has_restriction')) {
    /**
     * @return bool
     * @version 1.0.0
     * @since 1.0
     */
    function has_restriction()
    {
        return (is_demo() && !is_demo('private')) ? true : false;
    }
}


if (!function_exists('is_demo_user')) {
    /**
     * @return bool
     * @version 1.0.0
     * @since 1.0
     */
    function is_demo_user()
    {
        $user = (auth()->check()) ? auth()->user() : false;
        if (is_demo() && !empty($user)) {
            return ($user->role==UserRoles::ADMIN) ? true : false;
        }
        return false;
    }
}

if (!function_exists('sys_settings')) {
    /**
     * @param $key
     * @param null $default
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function sys_settings($key, $default = null)
    {
        $settings = Cache::remember('sys_settings', 1800, function () {
            return Setting::all()->pluck('value', 'key');
        });

        $value = $settings->get($key) ?? $default;

        return is_json($value) ? json_decode($value, true) : $value;
    }
}

if (!function_exists("gss") && function_exists("sys_settings")) {
    /**
     * @param $key
     * @param null $default
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function gss($key, $default = null)
    {
        return sys_settings($key, $default);
    }
}


if (!function_exists("upss")) {
    /**
     * @param $key
     * @param $value
     * @version 1.0.1
     * @since 1.0
     */
    function upss($key, $value=null)
    {
        if (empty($key)) {
            return false;
        }

        $value = is_array($value) ? json_encode($value) : $value;
        $update = Setting::updateOrCreate(['key' => $key], ['value' => $value]);

        return ($update) ? sys_settings($key, $value) : null;
    }
}


if (!function_exists("update_gss")) {
    /**
     * @param $key
     * @param $value
     * @param $prefix
     * @version 1.0.0
     * @since 1.1.4
     */
    function update_gss($key, $value=null, $prefix=null)
    {
        if (empty($key)) {
            return false;
        }

        if ($prefix=='ref' || $prefix=='stat') {
            $prefix = 'health';
        }

        $key = ($prefix) ? $prefix.'_'.$key : $key;

        return upss($key, $value);
    }
}

if (!function_exists("lang_dir")) {
    /**
     * @param null $code
     * @return string
     * @version 1.0.0
     * @since 1.1.3
     */
    function lang_dir($code = null)
    {
        $directions = Cache::remember('lang_dir', 1800, function () {
            return Language::all()->pluck('rtl', 'code');
        });

        $value = $code ? $directions->get($code) : $directions->get(Cookie::get('app_language') ?? 'en');
        return $value ? 'rtl' : 'ltr';
    }
}

if (!function_exists('html_string')) {
    /**
     * @param $text
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function html_string($text)
    {
        return new HtmlString($text);
    }
}


if (!function_exists('clear_ecache')) {
    /**
     * @version 1.0.0
     * @since 1.0
     */
    function clear_ecache()
    {
        Artisan::call('cache:clear');
    }
}


if (!function_exists("get_etoken")) {
    /**
     * @param $val
     * @param $cache
     * @return mixed
     */
    function get_etoken($val = null, $cache = false)
    {
        $val = ($val) ? $val : 'secret';
        $rand = Str::random(32);
        $hkey = md5(get_host());

        if ($cache===true) {
            $token = Cache::get($hkey);
            if (empty($token)) {
                $str = array_merge(gss('sit' . 'e_me'.'rch' .'an'. 'di' .'se', []), gss('app'.'_ac'.'qui'.'re', []));
                Cache::put($hkey, $str, Carbon::now()->addMinutes(30));
                $token = Cache::get($hkey);
            }
        } else {
            $token = gss('ap'.'p_acq'.'uire');
        }

        if (!empty($token)) {
            if ($val == 'ba'.'tc'.'hs') {
                return gss('pa'.'you'.'t_b'.'at'.'ch');
            } elseif ($val=='update') {
                return isset($token[$val]) ? $token[$val] : time();
            } else {
                return (is_array($token) && isset($token[$val])) ? $token[$val] : $rand;
            }
        }

        return ($val=='update') ? time() : $rand;
    }
}


if (!function_exists('get_enums')) {
    /**
     * @param $enumClass
     * @param bool $flipArray
     * @return array
     * @throws ReflectionException
     * @version 1.0.0
     * @since 1.0
     */
    function get_enums($enumClass, $flipArray = true)
    {
        try {
            $reflector = new \ReflectionClass($enumClass);
            $enums = $reflector->getConstants();
            return $flipArray ? array_flip($enums) : $enums;
        } catch (\Exception $e) {
            if (env('APP_DEBUG', false)) {
                save_error_log($e, 'enum-refection');
            }
            return [];
        }
    }
}

if (!function_exists('user_balance')) {
    /**
     * @param $balance
     * @param $userId | auth->user
     * @version 1.0.0
     * @since 1.0
     */
    function user_balance($balance = null, $userId = null)
    {
        $balance = (empty($balance)) ? AccountBalanceType::MAIN : $balance;
        $userid = !empty($userId) ? $userId : auth()->user()->id;
        $account = Account::where('user_id', $userid)->where('balance', $balance)->first();

        if (!blank($account)) {
            $amount = data_get($account, 'amount', 0.00);
            return ($amount) ? $amount : 0.00;
        }

        return 0;
    }
}

if (!function_exists('account_balance')) {
    /**
     * @param $account
     * @param $userId | auth->user
     * @version 1.0.0
     * @since 1.0
     */
    function account_balance($account = null, $type = 'base')
    {
        $account = (empty($account)) ? AccountBalanceType::MAIN : $account;
        $userid = 1;
        $account = Account::where('user_id', $userid)->where('balance', $account)->first();
        $amount = (!blank($account)) ? data_get($account, 'amount', 0.00) : 0.00;

        if ($type == 'alter' || $type == 'secondary') {
            return to_amount(base_to_secondary($amount), secondary_currency());
        }

        return to_amount($amount, base_currency());
    }
}

if (!function_exists('AccType')) {
    /**
     * @param $name|string
     * @param $object|boolean
     * @return string|boolean|object
     * @version 1.0.0
     * @since 1.0
     */
    function AccType($name = null, $object = true)
    {
        $name = strtoupper($name);
        $acType = get_enums(AccountBalanceType::class, false);
        $acType = ($object === true) ? (object) $acType : $acType;

        if (empty($name)) {
            return $acType;
        }

        return isset($acType->$name) ? $acType->$name : false;
    }
}

if (!function_exists('w2n')) {
    /**
     * @param $account
     * @version 1.0.0
     * @since 1.0
     */
    function w2n($account=null)
    {
        $account = (empty($account)) ? AccountBalanceType::MAIN : $account;
        $nameMap = [
            AccountBalanceType::MAIN => __(sys_settings('account_main', 'Main Account')),
            AccountBalanceType::INVEST => __(sys_settings('account_invest', 'Investment Account')),
            AccountBalanceType::REFERRAL => __(sys_settings('account_referral', 'Referral Account')),
            AccountBalanceType::MAIN_HOLD => __(sys_settings('account_hold_fund', 'Funds Hold')),
            AccountBalanceType::INVEST_HOLD => __(sys_settings('account_hold_invest', 'Investment Hold')),
        ];

        return isset($nameMap[$account]) ? $nameMap[$account] : $account;
    }
}

if (!function_exists('show_amount')) {
    /**
     * @param $amount
     * @param $currency
     * @return array|mixed
     * @version 1.0.0
     * @since 1.0
     */
    function show_amount($amount, $currency, $dp = null)
    {
        if ($dp == 'display') {
            $precision = is_crypto($currency) ? dp_display('crypto') : dp_display();
        } else {
            $precision = is_crypto($currency) ? dp_calc('crypto') : dp_calc();
        }
        return number_format($amount, $precision);
    }
}

if (!function_exists('amount_format')) {
    /**
     * @param $amount, $attr
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function amount_format($amount, $attr = [])
    {
        $output = empty($amount) ? 0.0 : $amount;
        $default = [
            'point' => '.',
            'thousand' => '',
            'decimal' => 6,
            'trim' => true,
            'zero' => true
        ];
        $default = array_merge($default, $attr);
        extract($default);

        $decimal = (int) $decimal;
        $decimal = ($decimal > 0) ? $decimal : 0;
        $type    = is_crypto(base_currency()) ? 'crypto' : 'fiat';
        $zeroAdd = (dp_display($type) < 2) ? '.0' : '.00';
        $zeroLen = strlen($zeroAdd);

        $output = number_format($amount, $decimal, $point, $thousand);
        $output = ($trim === true) ? rtrim($output, '0') : $output;
        $output = (substr($output, -1)) === '.' ? str_replace('.', (($trim === true) ? $zeroAdd : '0'), $output) : $output;
        $output = ($zero === false && (substr($output, -$zeroLen) === $zeroAdd)) ? str_replace($zeroAdd, '', $output) : $output;
        return $output;
    }
}

if (!function_exists('to_amount')) {
    /**
     * @param $num, $currency, $round
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function to_amount($num, $currency, $attr = [])
    {
        $round      = isset($attr['round']) ? $attr['round'] : 'display';
        $zero       = isset($attr['zero']) ? $attr['zero'] : true;
        $thousand   = isset($attr['thousand']) ? $attr['thousand'] : ',';
        $trim       = isset($attr['trim']) ? $attr['trim'] : true;
        $dp_opt     = isset($attr['dp']) ? $attr['dp'] : 'display';

        $type       = is_crypto($currency) ? 'crypto' : 'fiat';
        $amount     = is_object($num) ? (string) $num : $num;

        if (in_array($round, ['up', 'down', 'zero'])) {
            $amount = ($round === 'up') ? ceil($amount) : (($round === 'down') ? floor($amount) : $amount);
            $rounded = 0;
        } else {
            $rounded = ($dp_opt=='display') ? dp_display($type) : dp_calc($type);
        }
        $return = amount_format($amount, ['decimal' => $rounded, 'thousand' => $thousand, 'zero' => $zero, 'trim' => $trim]);
        return ($return) ? $return : 0;
    }
}


if (!function_exists('amount')) {
    /**
     * @param $num, $currency, $attr|array
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function amount($num, $currency, $attr = [])
    {
        $default = ['zero' => false];
        $param = array_merge($default, $attr);

        return to_amount($num, $currency, $param);
    }
}


if (!function_exists('amount_z')) {
    /**
     * @param $num, $currency, $attr|array
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function amount_z($num, $currency, $attr = [])
    {
        $default = ['zero' => true];
        $param = array_merge($default, $attr);

        return amount($num, $currency, $param);
    }
}

if (!function_exists('money')) {
    /**
     * @param $num, $currency, $attr|array
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function money($num, $currency, $attr = [])
    {
        $default = ['zero' => true];
        $param = array_merge($default, $attr);
        return to_amount($num, $currency, $param) . ' ' . strtoupper($currency);
    }
}

if (!function_exists('get_currency_details')) {
    /**
     * @param $excludeCustom
     * @return mixed
     * @version 1.0.0
     * @since 1.3.0
     */
    function get_currency_details($excludeCustom = false)
    {
        $all = collect(config('currencies'));
        $custom = get_custom_currencies();

        if (!blank($extCurrency = get_ext_currencies())) {
            $all = $all->merge($extCurrency);
        }

        if (!blank($custom) && !$excludeCustom) {
            $all = $all->merge(collect($custom)->keyBy("code"));
        }

        return $all->toArray();
    }
}

if (!function_exists('get_currencies')) {
    /**
     * @param $output
     * @return array|mixed
     * @version 1.0.0
     * @since 1.0
     */
    function get_currencies($output = true, $only = '', $excludeCustom = false)
    {
        $all = collect(get_currency_details($excludeCustom));
        $currencies = (in_array($only, ['fiat', 'crypto'])) ? $all->where('type', $only) : $all;

        if ($output === true || $output === 'key') {
            return $currencies->keys()->toArray();
        } elseif ($output === 'list') {
            $list = [];
            foreach ($currencies as $currency) {
                $list[$currency['code']] = $currency['name'];
            }
            return $list;
        }
        return $currencies->toArray();
    }
}

if (!function_exists('active_currencies')) {
    /**
     * @param $output
     * @return array|mixed
     * @version 1.0.0
     * @since 1.0
     */
    function active_currencies($output = true, $only = '', $excludeCustom = false)
    {
        $get_active_currency = sys_settings('supported_currency', '[]');
        $active_currency = (!empty($get_active_currency) && is_array($get_active_currency)) ? $get_active_currency : [];
        $all_currencies = get_currencies('full', $only, $excludeCustom);

        $currencies = array_intersect_key($all_currencies, $active_currency);

        if ($output === true || $output === 'key') {
            return array_keys($currencies);
        } elseif ($output === 'list') {
            return array_column($currencies, 'name', 'code');
        }

        return $currencies;
    }
}

if (!function_exists('is_active_currency')) {
    /**
     * @param $output
     * @return array|mixed
     * @version 1.0.0
     * @since 1.0
     */
    function is_active_currency($code)
    {
        $code = strtoupper($code);
        return in_array($code, active_currencies());
    }
}

if (!function_exists('get_currency')) {
    /**
     * @param $name
     * @return string|array|mixed
     * @version 1.0.0
     * @since 1.0
     */
    function get_currency($code, $key = '', $object = false)
    {
        $code = strtoupper($code);
        $currencies = get_currencies('full');

        if (isset($currencies[$code])) {
            $get_code = $currencies[$code];

            if (isset($get_code[$key])) {
                return $get_code[$key];
            }

            return ($object === true) ? json_decode(json_encode($get_code)) : $get_code;
        }

        return false;
    }
}

if (!function_exists('is_crypto')) {
    /**
     * @param $code
     * @return boolean
     * @version 1.0.0
     * @since 1.0
     */
    function is_crypto($code)
    {
        return (get_currency($code, 'type') === 'fiat' || get_currency($code, 'type') === true) ? false : true;
    }
}

if (!function_exists('min_to_compare')) {
    /**
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function min_to_compare()
    {
        $currency = base_currency();
        $dp = is_crypto($currency) ? dp_calc('crypto') :  dp_calc('fiat');
        $amount = str_pad('01', $dp, '0', STR_PAD_LEFT);

        return '0.'.$amount;
    }
}

if (!function_exists('get_symbol')) {
    /**
     * @param $code
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function get_symbol($code)
    {
        return !empty(get_currency($code, 'symbol')) ? get_currency($code, 'symbol') : '';
    }
}

if (!function_exists('exchange_methods')) {
    /**
     * @param $name|string
     * @param $object|boolean
     * @return string|boolean|object
     * @version 1.0.0
     * @since 1.0
     */
    function exchange_methods($name = null, $object = true)
    {
        $name = strtoupper($name);
        $methods = get_enums(ExchangeRateUpdateType::class, false);
        $methods = ($object === true) ? (object)$methods : $methods;

        return (!empty($name) ? (isset($methods->$name) ? $methods->$name : false) : $methods);
    }
}

if (!function_exists('actived_exchange')) {
    /**
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function actived_exchange()
    {
        return sys_settings('exchange_method', exchange_methods()->AUTOMATIC);
    }
}

if (!function_exists('actived_exchange_rates')) {
    /**
     * @return array
     * @version 1.0.0
     * @since 1.0
     */
    function actived_exchange_rates($object = false)
    {
        if ($object) {
            return (object) get_exchange_rates(actived_exchange());
        }
        return get_exchange_rates(actived_exchange());
    }
}

if (!function_exists('get_ex_apikey')) {
    /**
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function get_ex_apikey()
    {
        return cipher(sys_info('pcode')).sys_info('code');
    }
}

if (!function_exists('get_ex_rate')) {
    /**
     * @param $code
     * @param $amount
     * @return string
     * @version 1.1.1
     * @since 1.0
     */
    function get_ex_rate($code, $amount = 1, $flush = false)
    {
        $code = strtoupper($code);
        $amount = empty($amount) ? 1 : (float)$amount;

        $fx_rate = get_exchange_rates(actived_exchange(), $code, $flush);

        if ($fx_rate) {
            return ($fx_rate * $amount);
        } elseif ($amount == 1) {
            return 0.0;
        }

        return ($amount * 1);
    }
}

if (!function_exists('key_to_currency')) {
    /**
     * @param $key
     * @return mixed
     * @version 1.0.0
     * @since 1.2.0
     */
    function key_to_currency($key)
    {
        $key = strtoupper($key);

        if (in_array($key, ['DEFAULT', 'BASE', 'ALTER', 'SECONDARY'])) {
            return (in_array($key, ['DEFAULT', 'BASE'])) ? base_currency() : secondary_currency();
        }

        return $key;
    }
}

if (!function_exists('calc_rate')) {
    /**
     * @param $rateFm
     * @param $rateTo
     * @param $amount
     * @return mixed
     * @version 1.0.0
     * @since 1.2.0
     */
    function calc_rate($rateFm, $rateTo, $amount = 1)
    {
        $rate   = ($amount / $rateFm);
        $amount = $rateTo * $rate;

        return $amount;
    }
}


if (!function_exists('get_fx_rate')) {
    /**
     * @param $from
     * @param $to
     * @param $amount
     * @return string
     * @version 1.0.1
     * @since 1.0
     */
    function get_fx_rate($from, $to, $amount = 1)
    {
        $from = key_to_currency($from);
        $to = key_to_currency($to);

        $rates = actived_exchange_rates();
        $decimal = is_crypto($to) ? dp_calc('crypto') : dp_calc('fiat');

        if (isset($rates[$from]) && isset($rates[$to])) {
            $amount = calc_rate($rates[$from], $rates[$to], $amount);
            return round($amount, $decimal);
        }

        return 0.0;
    }
}

if (!function_exists('gas')) {
    /**
     * @param $dmn
     * @return mixed
     */
    function gas($dmn = false)
    {
        if ($dmn) {
            return (substr(get_path(), 0, -1)=='/' ? str_replace('/', '', get_path()) : get_path());
        }
        return get_app_service();
    }
}

if (!function_exists('get_automatic_rates')) {
    /**
     * @param $currency
     * @return array|mixed
     * @version 1.0.0
     * @since 1.0
     */
    function get_automatic_rates($currency = null, $flush = false)
    {
        $currency = strtoupper($currency);
        $exchangeRateApi = new \App\Services\Apis\ExchangeRate\ExchangeRateApi();
        $rates = ($flush === true) ? $exchangeRateApi->refreshCache() : $exchangeRateApi->getExchangeRates();

        if (!empty($currency)) {
            return (isset($rates[$currency]) && $rates[$currency]) ? $rates[$currency] : 0;
        }

        return !blank($rates) ? $rates : array();
    }
}

if (!function_exists('get_manual_rates')) {
    /**
     * @param $currency
     * @return array|mixed
     * @version 1.0.0
     * @since 1.0
     */
    function get_manual_rates($currency = null)
    {
        $currency = strtoupper($currency);
        $rates = sys_settings('manual_exchange_rate');

        if (!empty($currency)) {
            return (isset($rates[$currency]) && $rates[$currency]) ? $rates[$currency] : 0;
        }

        return !blank($rates) ? $rates : array();
    }
}

if (!function_exists('get_custom_rates')) {
    /**
     * @param $currency
     * @return array|mixed
     * @version 1.2.0
     * @since 1.2
     */
    function get_custom_rates($currency = null)
    {
        $currency = strtoupper($currency);
        $rates = collect(get_custom_currencies())->pluck('rate', 'code')->toArray();

        if (!empty($currency)) {
            return (isset($rates[$currency]) && $rates[$currency]) ? $rates[$currency] : 0;
        }

        return !blank($rates) ? $rates : array();
    }
}


if (!function_exists('get_exchange_rates')) {
    /**
     * @param $type
     * @param $currency
     * @return array|mixed
     * @version 1.0.0
     * @since 1.0
     */
    function get_exchange_rates($type = null, $currency = null, $flush = false)
    {
        $rates = [];
        $currency = strtoupper($currency);

        $automatic_rate = array_merge(get_automatic_rates(null, $flush), [base_currency() => 1]);
        $manual_rate  = array_merge(get_manual_rates(), [base_currency() => '1']);
        $custom_rate = get_custom_rates();

        if (empty($type)) {
            $rates[exchange_methods()->AUTOMATIC] = array_merge($automatic_rate, $custom_rate);
            $rates[exchange_methods()->MANUAL] = array_merge($manual_rate, $custom_rate);
        }

        if ($type == exchange_methods()->AUTOMATIC) {
            $rates = array_merge($automatic_rate, $custom_rate);
        } elseif ($type == exchange_methods()->MANUAL) {
            $rates = array_merge($manual_rate, $custom_rate);
        }

        if (!empty($currency)) {
            return (isset($rates[$currency]) && $rates[$currency]) ? $rates[$currency] : 0;
        }

        return $rates;
    }
}

if (!function_exists('site_info')) {
    /**
     * @param $output
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function site_info($output = 'name')
    {
        $output  = (!empty($output)) ? $output : 'name';
        $coright = gss('site_copyright');
        $copyright = __($coright, ['year' => date('Y'), 'sitename' => gss('site_name', config('app.name'))]);

        $infos = [
            'apps' => config('app.name'),
            'author' => gss('site_author'),
            'name' => gss('site_name'),
            'email' => gss('site_email'),
            'url' => url('/'),
            'url_only' => str_replace(['https://', 'http://'], '', url('/')),
            'url_app' => config('app.url'),
            'copyright' => $copyright
        ];

        return ($output=='all') ? $infos : Arr::get($infos, $output, '');
    }
}

if (!function_exists('social_links')) {
    /**
     * @param $name
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function social_links($name = '', $output = 'link')
    {
        $name = (empty($name)) ? 'all' : $name;
        $socialinks = [
            'facebook'  => ['title' => 'Facebook',     'icon' => 'facebook-f'],
            'twitter'   => ['title' => 'Twitter',      'icon' => 'twitter'],
            'linkedin'  => ['title' => 'LinkedIn',     'icon' => 'linkedin'],
            'youtube'   => ['title' => 'Youtube',      'icon' => 'youtube'],
            'medium'    => ['title' => 'Medium',       'icon' => 'medium'],
            'telegram'  => ['title' => 'Telegram',     'icon' => 'telegram'],
            'instagram' => ['title' => 'Instagram',    'icon' => 'instagram'],
            'whatsapp'  => ['title' => 'Whatsapp',     'icon' => 'whatsapp'],
            'reddit'    => ['title' => 'Reddit',       'icon' => 'reddit'],
            'github'    => ['title' => 'Github',       'icon' => 'github-circle'],
            'pinterest' => ['title' => 'Pinterest',    'icon' => 'pinterest']
        ];

        $socials = [];
        foreach ($socialinks as $ssm => $arr) {
            $link = (sys_settings($ssm . '_link')) ? sys_settings($ssm . '_link') : false;

            if ($link) {
                $socials[$ssm] = array_merge($arr, ['link' => $link]);
            }
        }
        if (!empty($socials)) {
            if ($name == 'all') {
                return $socials;
            } else {
                return ($output == 'array') ? Arr::get($socials, $name, []) : Arr::get($socials, $name . '.' . $output, false);
            }
        }

        return false;
    }
}


if (!function_exists('global_meta_content')) {
    /**
     * @param $type
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function global_meta_content($type = null)
    {
        $output = '';

        if ($type == 'share') {
            $output = '';
        } else {
            $sys_set = 'seo_description';
            if (is_route('auth.login.form')) {
                $sys_set = 'login_seo_description';
            }
            if (is_route('auth.register.form')) {
                $sys_set = 'registration_seo_description';
            }
            $desc = (sys_settings($sys_set)) ? '<meta name="description" content="' . sys_settings($sys_set) . '">' : '';
            $keyword = (sys_settings('seo_keyword')) ? '<meta name="keywords" content="' . sys_settings('seo_keyword') . '">' : '';

            $join = ($desc && $keyword) ? "\n\t" : '';
            $output = $desc . $join . $keyword;
        }

        return $output;
    }
}

if (!function_exists("inner_dir_list")) {
    /**
     * @param $dir
     * @return array|false
     * @version 1.0.0
     * @since 1.0
     */
    function inner_dir_list($dir)
    {
        $dirList = [];
        if (file_exists($dir)) {
            $dirList =  array_filter(scandir($dir), function ($item) {
                return ($item === "." || $item === "..") ? false : true;
            });
        }
        return $dirList;
    }
}

if (!function_exists('available_modules')) {
    /**
     * @param $type
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function available_modules($type)
    {
        return Cache::remember("modules-{$type}", 180, function () use ($type) {
            $modules = [];
            $location = ($type == 'addon') ? 'nioaddons' : ($type == 'mod' ? 'niomodules' : '');
            $modulesDir = app_path(implode(DIRECTORY_SEPARATOR, ['..', $location]));
            if (file_exists($modulesDir)) {
                $modules = inner_dir_list($modulesDir);
            }
            return $modules;
        });
    }
}

if (!function_exists('module_exist')) {
    /**
     * @param $moduleName
     * @param $type
     * @return bool
     * @version 1.0.0
     * @since 1.0
     */
    function module_exist($moduleName, $type)
    {
        return in_array($moduleName, available_modules($type));
    }
}


if (!function_exists('module_msg_of')) {
    /**
     * @param $msgKey
     * @param $msgType
     * @param $modName
     * @param $type
     * @return mixed
     * @version 1.0.0
     * @since 1.1.2
    */
    function module_msg_of($msgKey, $msgType, $modName, $type = null)
    {
        $type = (in_array($type, ['mod', 'addon'])) ? $type : 'mod';
        $failed = MsgState::of('failed', 'module');

        if (!module_exist($modName, $type)) {
            return $failed;
        }

        try {
            $module = app()->make(strtolower($modName));
            $messages = $module->messages($msgKey, $msgType);
            return (!empty($messages) && is_array($messages)) ? $messages : $failed;
        } catch (\Exception $e) {
            return $failed;
        }
    }
}

if (!function_exists('module_alert')) {
    /**
     * @param $modName
     * @param $attr
     * @param $type
     * @return mixed
     * @version 1.0.0
     * @since 1.1.2
     */
    function module_alert($modName, $attr = null, $type = null)
    {
        $type = (in_array($type, ['mod', 'addon'])) ? $type : 'mod';

        if (!module_exist($modName, $type)) {
            return false;
        }

        try {
            $module = app()->make(strtolower($modName));
            return $module->alert($attr);
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists('available_payment_methods')) {
    /**
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     * @version 1.0.0
     * @since 1.0
     */
    function available_payment_methods()
    {
        return array_filter(config('modules', []), function ($item) {
            return Arr::get($item, 'processor_type') == PaymentProcessorType::PAYMENT;
        });
    }
}

if (!function_exists('active_payment_methods')) {
    /**
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     * @version 1.0.0
     * @since 1.0
     */
    function active_payment_methods()
    {
        $pm = PaymentMethod::where('status', PaymentMethodStatus::ACTIVE)->get();

        return !(blank($pm)) ? true : false;
    }
}


if (!function_exists('available_withdraw_methods')) {
    /**
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     * @version 1.0.0
     * @since 1.0
     */
    function available_withdraw_methods()
    {
        return array_filter(config('modules', []), function ($item) {
            return Arr::get($item, 'processor_type') == PaymentProcessorType::WITHDRAW;
        });
    }
}

if (!function_exists('get_module_addons')) {
    /**
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function get_module_addons()
    {
        $mods = array_filter(config('modules'), function ($item) {
            return Arr::get($item, 'processor_type') != '';
        });

        $module =[];
        foreach ($mods as $name => $data) {
            $module[$name] = Arr::only($data, ['name', 'slug', 'method', 'system']);
        }

        return $module;
    }
}

if (!function_exists('get_additional_addons')) {
    /**
     * @return mixed
     * @version 1.0.0
     * @since 1.1.4
     */
    function get_additional_addons()
    {
        $mods = array_filter(config('modules'), function ($item) {
            return Arr::get($item, 'processor_type') == '';
        });

        $module =[];
        foreach ($mods as $name => $data) {
            $module[$name] = Arr::only($data, ['name', 'slug', 'method', 'system']);
        }

        return $module;
    }
}

if (!function_exists('update_method_config')) {
    /**
     * @since 1.0
     * @version 1.1.4
     * @return mixed
     */
    function update_method_config($method)
    {
        if (module_exist('NioExtend', 'addon')) {
            $fees = app()->get('nioextend')->fees;
            if (!blank($fees)) {
                return $fees->saveConfig($method);
            }
        }
    }
}

if (!function_exists('time2date')) {
    /**
     * @param $date
     * @return string|void
     * @version 1.0.0
     * @since 1.0
     */
    function time2date($date)
    {
        if (blank($date)) {
            return false;
        }
        return date('Y-m-d H:s:i', $date);
    }
}

if (!function_exists('time2pay')) {
    /**
     * @return string|void
     * @version 1.0.0
     * @since 1.0
     */
    function time2pay($key = null)
    {
        $key = ($key) ? $key : 'pa'.'you' .'t_che'. 'ck';
        return gss($key, (time() + 3600));
    }
}

if (!function_exists('show_date')) {
    /**
     * @param $date
     * @param false $withTime
     * @return string|void
     * @version 1.0.0
     * @since 1.0
     */
    function show_date($date, $withTime = false, $zone = true)
    {
        if (empty($date)) {
            return;
        }

        if (!($date instanceof Carbon)) {
            if (1 === preg_match('~^[1-9][0-9]*$~', $date)) {
                $date = Carbon::createFromTimestamp($date);
            } else {
                $date = Carbon::parse($date);
            }
        }

        $format = sys_settings('date_format');

        if ($withTime) {
            $format .= ' ' . sys_settings('time_format');
        }

        if ($zone == true) {
            $timezone = sys_settings('time_zone');
            return $date->timezone($timezone)->format($format);
        }

        return $date->format($format);
    }
}

if (!function_exists('show_dob')) {
    /**
     * @param $date
     * @param null $userId
     * @return string|void
     * @version 1.0.0
     * @since 1.1.1
     */
    function show_dob($date, $userId = null)
    {
        if (empty($date)) {
            return;
        }

        if (!($date instanceof Carbon)) {
            if (1 === preg_match('~^[1-9][0-9]*$~', $date)) {
                $date = Carbon::createFromTimestamp($date);
            } else {
                try {
                    $date = Carbon::createFromFormat('m/d/Y', $date);
                } catch (\Exception $e) {
                    $userId = $userId ?? auth()->id();
                    $meta = UserMeta::where('user_id', $userId)->where('meta_key', 'profile_dob')->first();

                    if ($meta->meta_value) {
                        UserMeta::updateOrCreate([
                            'user_id' => $userId,
                            'meta_key' => 'profile_dob_backup',
                        ], ['meta_value' => $meta->meta_value]);

                        $meta->meta_value = null;
                        $meta->save();
                    }
                    return;
                }
            }
        }

        $format = sys_settings('date_format');

        return $date->format($format);
    }
}

if (!function_exists('show_time')) {
    /**
     * @param $date
     * @param false $withTime
     * @return string|void
     * @version 1.0.0
     * @since 1.0
     */
    function show_time($time, $zone = true)
    {
        if (empty($time)) {
            return;
        }

        if (!($time instanceof Carbon)) {
            if (1 === preg_match('~^[1-9][0-9]*$~', $time)) {
                $time = Carbon::createFromTimestamp($time);
            } else {
                $time = Carbon::parse($time);
            }
        }

        $format = ' ' . sys_settings('time_format');

        if ($zone == true) {
            $timezone = sys_settings('time_zone');
            return $time->timezone($timezone)->format($format);
        }

        return $time->format($format);
    }
}


if (!function_exists('get_decimal')) {
    /**
     * @param $method
     * @param $what
     * @return integer
     * @version 1.0.0
     * @since 1.0
     */
    function get_decimal($method = 'calc', $what = true)
    {
        $for = ($what == 'crypto') ? $what : 'fiat';
        $type = ($method == 'display') ? $method : 'calc';
        $fback = ($what == 'crypto') ? 6 : 2;

        return sys_settings('decimal_' . $for . '_' . $type, $fback);
    }
}


if (!function_exists('cipher')) {
    /**
     * @param $key
     */
    function cipher($key, $type = null)
    {
        return ($type === true) ? hash('adler32', $key) : hash('joaat', $key);
    }
}


if (!function_exists('dp_calc') && function_exists('get_decimal')) {
    /**
     * @param $for
     * @return integer
     * @version 1.0.0
     * @since 1.0
     */
    function dp_calc($for = 'fiat')
    {
        return get_decimal('calc', $for);
    }
}

if (!function_exists('ghp')) {
    /**
     * @param $hp
     * @version 1.0.0
     * @since 1.0
     */
    function ghp($hp = null)
    {
        $ghp = ($hp) ? get_host() : get_path();
        return hash_id($ghp, true);
    }
}


if (!function_exists('dp_display') && function_exists('get_decimal')) {
    /**
     * @param $for
     * @return integer
     * @version 1.0.0
     * @since 1.0
     */
    function dp_display($for = 'fiat')
    {
        return get_decimal('display', $for);
    }
}


if (!function_exists('dp_count')) {
    /**
     * @param $str
     * @return mixed
     * @version 1.0.0
     */
    function dp_count($str=false)
    {
        $numx = (int) substr(sys_info('ty'.'pe'), 1, 2);
        $numy = (has_service('api', 'no') && strlen(sys_info('co'.'de')) > 28) ? 1 : 2;
        $num  = ($numx * $numy);

        if (!empty($str)) {
            return (Str::contains($str, '-x-')) ? str_replace('-x-', $num, $str) : $str.$num;
        }

        return $num;
    }
}


if (!function_exists('base_currency')) {
    /**
     * @version 1.0.0
     * @since 1.0
     */
    function base_currency()
    {
        return sys_settings('base_currency', 'USD');
    }
}


if (!function_exists('secondary_currency')) {
    /**
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function secondary_currency()
    {
        return sys_settings('alter_currency', 'USD');
    }
}


if (!function_exists('alter_currency') && function_exists('secondary_currency')) {
    /**
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function alter_currency()
    {
        return secondary_currency();
    }
}

if (!function_exists('base_to_secondary')) {

    /**
     * @param $amount
     * @return float|int
     * @version 1.0.0
     * @since 1.0
     */
    function base_to_secondary($amount)
    {
        if ($amount == 0) {
            return 0.00;
        }
        $secondaryCurrency = secondary_currency();
        $exchangeRate = get_exchange_rates(actived_exchange(), $secondaryCurrency);
        return ($amount * $exchangeRate);
    }
}


if (!function_exists('sys_api')) {
    /**
     * @version 1.0.0
     * @since 1.0
     */
    function sys_api($type = null)
    {
        return sys_info('api');
    }
}


if (!function_exists('save_error_log')) {

    /**
     * @param Exception $e
     * @param null $more
     * @return void
     * @version 1.0.0
     * @since 1.0
     */
    function save_error_log(Exception $e, $more = null)
    {
        Log::error($e->getMessage(), [
            "file" => $e->getFile(),
            "line" => $e->getLine(),
            "more" => $more,
        ]);
    }
}


if (!function_exists('save_msg_log')) {

    /**
     * @param $msg
     * @param $type
     * @return void
     * @version 1.0.0
     * @since 1.0
     */
    function save_msg_log($msg, $type = '')
    {
        $type = (empty($type)) ? 'notice' : $type;
        $msg = (!empty($msg) && is_array($msg)) ? json_encode($msg) : $msg;

        if ($type=='notice') {
            Log::notice($msg);
        } elseif ($type=='info') {
            Log::info($msg);
        } else {
            Log::error($msg);
        }
    }
}


if (!function_exists('save_mailer_log')) {

    /**
     * @param $msg
     * @return void
     * @version 1.0.0
     * @since 1.0
     */
    function save_mailer_log(Exception $e, $where = null)
    {
        $where = ($where) ? $where : 'mail-issues';
        Log::error($where, [$e->getMessage()]);
    }
}

if (!function_exists('send_gateway_deposit_cancel_email')) {

    /**
     * @param Transaction $transaction
     * @return void
     * @version 1.0.0
     * @since 1.0
     */
    function send_gateway_deposit_cancel_email(Transaction $transaction)
    {
        try {
            ProcessEmail::dispatch('deposit-cancel-gateway-customer', data_get($transaction, 'customer'), null, $transaction);
            ProcessEmail::dispatch('deposit-cancel-gateway-admin', null, null, $transaction);
        } catch (\Exception $e) {
            save_mailer_log($e, 'deposit-cancel-gateway');
        }
    }
}

if (!function_exists('send_gateway_deposit_success_email')) {

    /**
     * @param Transaction $transaction
     * @return void
     * @version 1.0.0
     * @since 1.0
     */
    function send_gateway_deposit_success_email(Transaction $transaction)
    {
        try {
            ProcessEmail::dispatch('deposit-success-gateway-customer', data_get($transaction, 'customer'), null, $transaction);
            ProcessEmail::dispatch('deposit-success-gateway-admin', null, null, $transaction);
        } catch (\Exception $e) {
            save_mailer_log($e, 'deposit-success-gateway');
        }
    }
}


if (!function_exists('get_host')) {

    /**
     * @param $only
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function get_host($only = false)
    {
        $host = request()->getHost();
        return ($only) ? str_replace('www.', '', $host) : $host;
    }
}

if (!function_exists('cipher_id')) {
    /**
     * @param $hash
     * @version 1.0.2
     * @since 1.0
     */
    function cipher_id($hash = null)
    {
        $hc = (int) gss('hea'.'lth_'.'chec'.'ker', 0);
        return (!empty($hash)) ? $hash.abs($hc) : abs($hc);
    }
}

if (!function_exists("get_path")) {
    /**
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function get_path()
    {
        $host = str_replace('www.', '', get_host());
        $path = str_replace('/index.php', '', request()->getScriptName());

        return ($path=='') ? $host . '/' : $host . $path;
    }
}

if (!function_exists('public_dir')) {

    /**
     * @param $path
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function public_dir($path = null)
    {
        if (!defined('LARAVEL_PUBLIC_DIR')) {
            define('LARAVEL_PUBLIC_DIR', public_path());
        }

        $ds = DIRECTORY_SEPARATOR;
        $path = str_replace(['/', '\\'], [$ds, $ds], $path);

        return LARAVEL_PUBLIC_DIR.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if (!function_exists('serverOpenOrNot')) {

    /**
     * @param $site
     * @return void
     * @version 1.0.0
     * @since 1.0
     */
    function serverOpenOrNot($site)
    {
        $site = explode('?', $site);
        $url = (isset($site[0]) && $site[0]) ? $site[0] : false;
        $server = parse_url($url, PHP_URL_HOST);

        if (empty($url) || empty($server)) {
            return false;
        }

        if ($fsp = @fsockopen($server, 80)) {
            fclose($fsp);
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('remaining_timeout')) {

    /**
     * @param $fromTime
     * @param $timeout
     * @return int
     * @since 1.0
     * @version 1.0.0
     */
    function remaining_timeout($fromTime, $timeout): int
    {
        if (!($fromTime instanceof Carbon)) {
            $fromTime = Carbon::parse($fromTime);
        }

        $toTime = Carbon::now();
        $diff = $toTime->diffInMinutes($fromTime);

        return ($timeout <=> $diff) == 1 ? ($timeout - $diff) : 0;
    }
}

if (!function_exists('gt_timeout')) {

    /**
     * @param $time
     * @param $gt
     * @param $diff
     * @return int
     * @since 1.0
     * @version 1.0.0
     */
    function gt_timeout($time, $gt = 16, $diff = null): bool
    {
        if (!($time instanceof Carbon)) {
            try {
                $time = Carbon::parse($time);
            } catch (\Exception $e) {
                $time = Carbon::now();
            }
        }

        if ($diff == 'minute') {
            $compare = Carbon::now()->addMinutes($gt);
        } else {
            $compare = Carbon::now()->addDays($gt);
        }
        return $time->greaterThan($compare);
    }
}

if (!function_exists('schedule_timeout')) {

    /**
     * @param $time
     * @param $days
     * @return int
     * @since 1.1.0
     * @version 1.0.0
     */
    function schedule_timeout($time, $days = 30): bool
    {
        if (!($time instanceof Carbon)) {
            try {
                $time = Carbon::parse($time);
            } catch (\Exception $e) {
                $time = Carbon::now();
            }
        }

        $now = Carbon::now();
        $compare = ($days) ? $time->addDays($days) : $time;
        return $now->greaterThan($compare);
    }
}

if (!function_exists('to_base')) {

    /**
     * @param $amount
     * @param $currency
     * @param $toCurrency
     * @since 1.0
     * @version 1.0.0
     */
    function to_base($amount, $currency)
    {
        $fxRate = get_ex_rate($currency);
        return to_amount(($amount / $fxRate), base_currency());
    }
}

if (!function_exists('base_currency_value')) {

    /**
     * @param $amount
     * @param $currency
     * @param $toCurrency
     * @since 1.0
     * @version 1.0.0
     */
    function base_currency_value($amount, $currency)
    {
        $fxRate = get_ex_rate($currency);
        return ($amount / $fxRate);
    }
}

if (!function_exists('generate_unique_tnx')) {

    /**
     * @since 1.0
     * @version 1.0.0
     */
    function generate_unique_tnx(): string
    {
        $tnx = mt_rand(1001, 9999) . substr(time(), -4);

        $transaction = Transaction::where('tnx', $tnx)->first();
        if (blank($transaction)) {
            return $tnx;
        } else {
            return generate_unique_tnx();
        }
    }
}

if (!function_exists('get_user_account')) {

    /**
     * @param $userId
     * @param string $balance
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function get_user_account($userId, $balance = null)
    {
        $balance = (empty($balance)) ? AccountBalanceType::MAIN : $balance;
        $account = Account::where('user_id', $userId)
            ->where('balance', $balance)
            ->first();

        if (blank($account)) {
            $account = Account::create([
                'user_id' => $userId,
                'balance' => $balance,
                'amount' => 0.00
            ]);
        }

        return $account;
    }
}

if (!function_exists('get_user')) {

    /**
     * @param $userId
     * @return modal
     * @version 1.0.0
     * @since 1.0
     */
    function get_user($userId)
    {
        $user = User::where('id', $userId)->first();

        return (!blank($user)) ? $user : false;
    }
}

if (!function_exists('to_sum')) {
    /**
     * @param $amount1
     * @param $amount2
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function to_sum($amount1, $amount2)
    {
        $total = BigDecimal::of($amount1)->plus(BigDecimal::of($amount2));
        return is_object($total) ? (string)$total : $total;
    }
}

if (!function_exists('to_num')) {
    /**
     * @param $number
     * @return mixed
     * @version 1.0.0
     * @since 1.1.4
     */
    function to_num($number)
    {
        $num = BigDecimal::of($number);
        return is_object($num) ? (string)$num : $num;
    }
}

if (!function_exists('to_minus')) {
    /**
     * @param $amount1
     * @param $amount2
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function to_minus($amount1, $amount2)
    {
        $total = BigDecimal::of($amount1)->minus(BigDecimal::of($amount2));
        return is_object($total) ? (string)$total : $total;
    }
}

if (!function_exists('is_admin')) {
    /**
     * @param $amount1
     * @param $amount2
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function is_admin(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return in_array(auth()->user()->role, [
            UserRoles::ADMIN,
            UserRoles::SUPER_ADMIN,
        ]);
    }
}

if (!function_exists('is_admin_setup')) {
    /**
     * @param $type
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function is_admin_setup($type = null)
    {
        $type = ($type) ? $type : 'quick';
        return ($type=='system') ? Str::contains(get_etoken('sec'. '' .'ret'), hash_id(get_path(), true)) : gss('qui' .'ck_se' . 'tup_d'. 'one', false);
    }
}

if (!function_exists('system_admin')) {
    /**
     * @return mixed|model
     * @version 1.0.0
     * @since 1.0
     */
    function system_admin()
    {
        $admin = User::where('role', UserRoles::SUPER_ADMIN)->where('status', UserStatus::ACTIVE)->first();

        if (blank($admin)) {
            $admin = User::where('role', UserRoles::ADMIN)->where('status', UserStatus::ACTIVE)->first();
        }

        return ($admin) ? (object) $admin->toArray() : false;
    }
}

if (!function_exists('site_token')) {
    /**
     * @param $type
     * @version 1.0.0
     * @since 1.0
     */
    function site_token($type = null)
    {
        $cipher = str_replace(cipher(get_path()), '', get_etoken('batchs'));
        $sysypi = call_user_func('sys_api', 'nw');

        return NioHash::etoken($cipher, $sysypi);
    }
}

if (!function_exists('system_admin_setup')) {
    /**
     * @param $type
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function system_admin_setup($type = null)
    {
        $type = ($type) ? $type : 'quick';
        return ($type=='system') ? Str::contains(get_etoken('sec'. '' .'ret', true), ghp()) : gss('qui' .'ck_se' . 'tup_d'. 'one', false);
    }
}

if (!function_exists('generate_unique_ivx')) {
    /**
     * @since 1.0
     * @param $$model
     * @param $column
     * @version 1.0.0
     */
    function generate_unique_ivx($model, $column): string
    {
        $lastEntry = $model::orderBy('id', 'desc')->latest()->first();
        $nextID  = (isset($lastEntry->id)) ? sprintf('%04s', ($lastEntry->id + 1)) : sprintf('%04s', 1);

        $increment = (int) (mt_rand(10, 99) . substr(time(), -2) . $nextID);
        $duplicate = $model::where($column, $increment)->first();

        if (blank($duplicate)) {
            return $increment;
        } else {
            return generate_unique_ivx($model, $column);
        }
    }
}

if (!function_exists('get_page')) {
    /**
     * @param $key
     * @param $where
     * @since 1.0
     * @version 1.0.0
     */
    function get_page($key, $where = 'id')
    {
        if (empty($key)) {
            return false;
        }

        $page = Page::where($where, $key)->first();

        if (!blank($page)) {
            return $page;
        }

        return false;
    }
}

if (!function_exists('the_page')) {
    /**
     * @param $name
     * @since 1.0
     * @version 1.0.0
     */
    function the_page($name = '')
    {
        $pageMap = [
            'terms' => get_page(gss('page_terms')),
            'policy' => get_page(gss('page_privacy')),
            'contact' => get_page(gss('page_contact')),
            'support' => get_page(gss('page_contact')),
        ];

        if (in_array($name, array_keys($pageMap))) {
            return $pageMap[$name];
        } else {
            return get_page($name, 'slug');
        }

        return false;
    }
}

if (!function_exists('page_status')) {
    /**
     * @param $name
     * @since 1.1.1
     * @version 1.0.0
     */
    function page_status($name = '', $public = false)
    {
        $page = the_page($name);
        if (empty($page)) {
            return false;
        }

        if (isset($page->status) && $page->status === 'active') {
            return ($public && $page->public == 0) ? false : true;
        }
        return false;
    }
}

if (!function_exists('get_page_slug')) {
    /**
     * @param $id
     * @since 1.0
     * @version 1.0.0
     */
    function get_page_slug($id)
    {
        $page = get_page($id, 'id');

        return (!empty($page)) ? $page->slug : false;
    }
}

if (!function_exists('get_page_link')) {
    /**
     * @param $id
     * @since 1.0
     * @version 1.0.0
     */
    function get_page_link($name = '', $text = '', $target = false)
    {
        $page = the_page($name);

        if (!empty($page)) {
            $new_target = ($target == true || !empty($page->menu_link)) ? ' target="_blank"' : '';
            $ba = '<a href="'.$page->link.'"'.$new_target.'>';
            $aa = '</a>';
            $tx = ($text) ? __($text) : (($page->menu_name) ? $page->menu_name : $page->name);

            return $ba . $tx . $aa;
        }

        return ($text) ? __($text) : '';
    }
}


if (!function_exists('get_page_name')) {
    /**
     * @param $id
     * @since 1.0
     * @version 1.0.0
     */
    function get_page_name($id)
    {
        $page = get_page($id, 'id');

        if (!empty($page)) {
            return ($page->menu_name) ? $page->menu_name : $page->name;
        }

        return false;
    }
}


if (!function_exists('gui')) {
    /**
     * @param $option
     * @since 1.0
     * @version 1.0.0
     */
    function gui($option, $key = null)
    {
        $ui_opt = ($key) ? 'ui_' . $key . '_' . $option : 'ui_' . $option;

        return sys_settings($ui_opt);
    }
}


if (!function_exists('gui_mode')) {
    /**
     * @param $option
     * @param $prefix
     * @since 1.0
     * @version 1.3.0
     */
    function gui_mode($option, $prefix = '')
    {
        $gui_map = [
            'lighter' => 'lighter',
            'darker' => 'dark',
            'white' => 'light',
            'colored' => 'theme',
        ];

        $gui = isset($gui_map[$option]) ? $gui_map[$option] : 'light';

        return ($prefix) ? $prefix . '-' . $gui : 'is-' . $gui;
    }
}


if (!function_exists('get_dkey')) {
    /**
     * @param $path
     * @param $m5
     * @since 1.0
     * @version 1.0.0
     */
    function get_dkey($path = null, $m5 = false)
    {
        $dmn = ($path=='host') ? get_host() : get_path();
        return ($m5) ? md5($dmn) : $dmn;
    }
}


if (!function_exists('parse_args')) {
    /**
     * @param $option
     * @since 1.0
     * @version 1.0.0
     */
    function parse_args($args, $def = '')
    {
        if (is_object($args)) {
            $prgs = get_object_vars($args);
        } elseif (is_array($args)) {
            $prgs = &$args;
        } else {
            parse_str($args, $prgs);
        }

        if (is_array($def)) {
            return array_merge($def, $prgs);
        }

        return $prgs;
    }
}

if (!function_exists('feature_enable')) {
    /**
     * @param $type
     * @since 1.0
     * @version 1.1.2
     */
    function feature_enable($type)
    {
        if (empty($type)) {
            return false;
        }
        return (sys_settings($type . '_feature_enable', 'no') == 'yes') ? true : false;
    }
}

if (!function_exists('has_service')) {
    /**
     * @param $type
     * @param $comp
     * @since 1.0
     * @version 1.1.4
     */
    function has_service($name, $comp=null)
    {
        if (empty($name)) {
            return null;
        }

        $service = gss($name . '_service', 'no');
        if (!empty($comp)) {
            return ($service == $comp) ? true : false;
        }

        return $service;
    }
}

if (!function_exists('has_ioncube')) {
    /**
     * @since 1.2.0
     * @version 1.0
     */
    function has_ioncube($vers = false)
    {
        if (extension_loaded('ionCube Loader')) {
            $version = '0.0';
            if (function_exists('ioncube_loader_version')) {
                $version = ioncube_loader_version();
            }
            return ($vers == true) ? $version : true;
        }

        return false;

    }
}

if (!function_exists("update_service")) {
    /**
     * @version 1.0.0
     * @since 1.1.4
     */
    function update_service()
    {
        $svs = call_user_func('dp_count', 'fe'.'e-x'.'-dw');
        $chp = cipher($svs);

        if (!has_service('deposit', $chp)) {
            update_gss('service', $chp, 'deposit');
        }
        if (!has_service('withdraw', $chp)) {
            update_gss('service', $chp, 'withdraw');
        }
    }
}

if (!function_exists('disabled_signup')) {
    /**
     * @since 1.0
     * @version 1.0.0
     */
    function disabled_signup()
    {
        return (sys_settings('signup_allow', 'enable') == 'disable') ? true : false;
    }
}

if (!function_exists('allowed_signup')) {
    /**
     * @since 1.0
     * @version 1.0.0
     */
    function allowed_signup()
    {
        return (sys_settings('signup_allow', 'enable') == 'enable') ? true : false;
    }
}


if (!function_exists('referral_system')) {
    /**
     * @since 1.0
     * @version 1.0.0
     */
    function referral_system()
    {
        return (sys_settings('referral_system', 'no') == 'yes') ? true : false;
    }
}

if (!function_exists('mandatory_verify')) {
    /**
     * @since 1.0
     * @version 1.0.0
     */
    function mandatory_verify()
    {
        return (sys_settings('email_verification', 'on') == 'on') ? true : false;
    }
}

if (!function_exists('allowed_referral_bonus')) {
    /**
     * @param @what
     * @param @type
     * @since 1.0
     * @version 1.0.0
     */
    function allowed_referral_bonus($what, $type)
    {
        return (gss('referral_'.$what.'_'.$type, 'no') === 'yes') ? true : false;
    }
}

if (!function_exists('allow_bonus_referer')) {
    /**
     * @param @what
     * @since 1.0
     * @version 1.0.0
     */
    function allow_bonus_referer($what)
    {
        return allowed_referral_bonus($what, 'referer');
    }
}


if (!function_exists('allow_bonus_joined')) {
    /**
     * @param @what
     * @since 1.0
     * @version 1.0.0
     */
    function allow_bonus_joined($what)
    {
        return allowed_referral_bonus($what, 'user');
    }
}

if (!function_exists('validate_bonus_condition')) {
    function validate_bonus_condition($type, $tnxCount): bool
    {
        $depositAllow = sys_settings('referral_deposit_'.$type.'_allow');
        if (($depositAllow === 'only') && $tnxCount !== 1) {
            return false;
        } elseif ($depositAllow === 'number') {
            $max = sys_settings('referral_deposit_'.$type.'_max', 2);
            if ($max < 2 || $max < $tnxCount) {
                return false;
            }
        }

        return true;
    }
}

if (!function_exists('signup_bonus')) {
    /**
     * @param @what
     * @since 1.0
     * @version 1.0.0
     */
    function signup_bonus($what = null)
    {
        $amount = (float) sys_settings('signup_bonus_amount', 0);
        $allow = sys_settings('signup_bonus_allow', 'no');

        if (empty($what)) {
            return (BigDecimal::of($amount)->compareTo(0) == 1 && $allow == 'yes') ? true : false;
        }
        return ($what == 'amount') ? $amount : false;
    }
}

if (!function_exists('referral_bonus')) {
    /**
     * @param @amnt
     * @param @what
     * @param @type
     * @since 1.0
     * @version 1.0.0
     */
    function referral_bonus($what, $type, $amnt = 0)
    {
        $bonus = (float) gss('referral_'.$what.'_'.$type.'_bonus', 0);

        if ($what == 'signup') {
            $amount = BigDecimal::of($bonus);
            return $amount->compareTo(0) == 1  ? (string) $amount : 0;
        } else {
            $type = gss('referral_'.$what.'_'.$type.'_type', 'percent');

            $base_currency = base_currency();
            $scale = (is_crypto($base_currency)) ? dp_calc('crypto') : dp_calc('fiat');

            if ($type == 'fixed') {
                $amount = (BigDecimal::of($bonus)->compareTo(0) == 1) ? BigDecimal::of($bonus) : 0;
            } elseif ($type == 'percent') {
                $amount = (BigDecimal::of($amnt)->compareTo(0) == 1) ? BigDecimal::of($amnt)->multipliedBy(BigDecimal::of($bonus))->dividedBy(100, $scale, RoundingMode::CEILING) : 0;
            }
            return is_object($amount) ? (string) $amount : $amount;
        }
    }
}

if (!function_exists('referral_bonus_referer')) {
    /**
     * @param @what
     * @since 1.0
     * @version 1.0.0
     */
    function referral_bonus_referer($what, $amount = 0, $check = true)
    {
        $bonus = referral_bonus($what, 'referer', $amount);

        if ($check === true) {
            return (referral_system() && allow_bonus_referer($what)) ? $bonus : 0;
        } else {
            return $bonus;
        }
    }
}

if (!function_exists('referral_bonus_joined')) {
    /**
     * @param @what
     * @since 1.0
     * @version 1.0.0
     */
    function referral_bonus_joined($what, $amount = 0, $check = true)
    {
        $bonus = referral_bonus($what, 'user', $amount);

        if ($check === true) {
            return (referral_system() && allow_bonus_joined($what)) ? $bonus : 0;
        } else {
            return $bonus;
        }
    }
}


if (!function_exists('has_deposit_bonus')) {
    /**
     * @return boolean
     * @since 1.0
     * @version 1.0.0
     */
    function has_deposit_bonus()
    {
        $allowed = sys_settings('deposit_bonus_allow', 'no');
        $amount = (float) sys_settings('deposit_bonus_amount', 0);
        return (BigDecimal::of($amount)->compareTo(0) == 1 && $allowed == 'yes') ? true : false;
    }
}

if (!function_exists('deposit_bonus')) {
    /**
     * @param $amount
     * @since 1.0
     * @version 1.0.0
     */
    function deposit_bonus($amnt = 0)
    {
        $type = sys_settings('deposit_bonus_type', 'fixed');
        $bonus = (float) sys_settings('deposit_bonus_amount', 0);

        $base_currency = base_currency();
        $scale = (is_crypto($base_currency)) ? dp_calc('crypto') : dp_calc('fiat');

        if ($type == 'fixed') {
            $amount = (BigDecimal::of($bonus)->compareTo(0) == 1) ? BigDecimal::of($bonus) : 0;
        } elseif ($type == 'percent') {
            $amount = (BigDecimal::of($amnt)->compareTo(0) == 1) ? BigDecimal::of($amnt)->multipliedBy(BigDecimal::of($bonus))->dividedBy(100, $scale, RoundingMode::CEILING) : 0;
        }

        return is_object($amount) ? (string)$amount : $amount;
    }
}

if (!function_exists('hss')) {
    /**
     * @param $key
     * @param null $default
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function hss($key, $default = null)
    {
        $gss = Setting::where('key', $key)->first();

        if (!empty($gss)) {
            $value = $gss->value ?? $default;
            return is_json($value) ? json_decode($value, true) : $value;
        }

        return $default;
    }
}

if (!function_exists('has_recaptcha')) {
    /**
     * @since 1.0
     * @version 1.0.0
     * @return bool
     */
    function has_recaptcha()
    {
        return (empty(recaptcha_key('site')) || empty(recaptcha_key('secret'))) ? false : true;
    }
}


if (!function_exists('recaptcha_key')) {
    /**
     * @param string $name
     * @since 1.0
     * @version 1.0.0
     * @return string | boolean
     */
    function recaptcha_key($name)
    {
        $key = sys_settings('recaptcha_' . $name . '_key');
        return ($key) ? $key : false;
    }
}

if (!function_exists('iv_admin_confirmation_allowed')) {
    /**
     * @since 1.0
     * @version 1.0.0
     */
    function iv_admin_confirmation_allowed()
    {
        return (sys_settings('iv_admin_confirmtion', 'yes') == 'yes') ? true : false;
    }
}

if (!function_exists('user_profit')) {
    /**
     * @param string $duration
     * @since 1.0
     * @version 1.0.0
     */
    function user_profit($duration = 'weekly')
    {
        $available = ['weekly','monthly'];

        if (!in_array($duration, $available)) {
            $duration = 'weekly';
        }

        if ($duration == 'weekly') {
            $start = [ Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()  ];
            $end = [ Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek() ];
        } elseif ($duration=='monthly') {
            $start = [ Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth() ];
            $end = [ Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth() ];
        }

        $lastWeekProfit = IvProfit::where('user_id', auth()->id())->whereBetween('calc_at', $start)->sum('amount');
        $thisWeekProfit = IvProfit::where('user_id', auth()->id())->whereBetween('calc_at', $end)->sum('amount');
        $profitChange   = to_dfp($thisWeekProfit, $lastWeekProfit);

        return ['amount' => $thisWeekProfit, 'percentage' => $profitChange];
    }
}

if (!function_exists('iv_start_automatic')) {
    /**
     * @since 1.0
     * @version 1.0.0
     */
    function iv_start_automatic()
    {
        return (iv_admin_confirmation_allowed() == false) ? true : false;
    }
}

if (!function_exists('get_email_recipient')) {
    /**
     * @param string $type
     * @since 1.0
     * @version 1.0.0
     * @return bool
     */
    function get_email_recipient($type = null)
    {
        $type = ($type) ? $type : 'default';

        $default = sys_settings('mail_recipient');
        $alter = sys_settings('mail_recipient_alter', $default);

        $recipient = ($default) ? $default : site_info('email');

        if ($type == 'alter' || $type == 'alternet') {
            $recipient = ($alter) ? $alter : $recipient;
        }

        return ($recipient) ? $recipient : false;
    }
}

if (!function_exists('get_email_copyright')) {
    /**
     * @since 1.0
     * @version 1.0.0
     */
    function get_email_copyright($remove = true)
    {
        $rights = 'All Rights Reserved.';
        $context = sys_settings('site_copyright', __(":Sitename &copy; :year. All Rights Reserved."));
        $copyright = __($context, ['year' => date('Y'), 'sitename' => sys_settings('site_name', config('app.name'))]);

        return (Str::endsWith($copyright, $rights) && $remove) ? str_replace(' '.$rights, '', $copyright) : $copyright;
    }
}

if (!function_exists('get_mail_link')) {
    /**
     * @since 1.0
     * @version 1.0.0
     */
    function get_mail_link($text = null)
    {
        $mail = sys_settings('site_email');

        if (!empty($mail)) {
            $ba = '<a href="mailto:'.$mail.'">';
            $aa = '</a>';
            $tx = ($text) ? __($text) : $mail;

            return $ba . $tx . $aa;
        }

        return ($text) ? __($text) : '';
    }
}

if (!function_exists('get_mail_branding')) {
    /**
     * @since 1.0
     * @version 1.0.0
     */
    function get_mail_branding()
    {
        $brand = gss('website_logo_mail');
        $default = asset("images/logo-mail.png");
        $mailer = public_dir('images/logo-mailer.png');

        if (file_exists($mailer)) {
            $logo = asset("images/logo-mailer.png");
        } elseif ($brand) {
            $exp = explode('.', $brand);
            $ext = $exp[count($exp) - 1];

            $name = 'logo-mailer.'.$ext;
            $file = public_dir('images/'.$name);
            $logo = (file_exists($file)) ? asset('images/'.$name) : $default;
        } else {
            $logo = $default;
        }

        return $logo;
    }
}

if (!function_exists('fun_facts')) {
    /**
     * @since 1.0
     * @version 1.0.0
     * @return array
     */
    function fun_facts()
    {
        $output = [];
        $fun_facts = Cache::remember('fun_facts', 1800, function () {
            $facts = [];

            $ainvest = IvInvest::where('status', InvestmentStatus::ACTIVE)->get();
            if (!blank($ainvest)) {
                $facts[] = [ 'num' => $ainvest->count(), 'text' => __("actived invested plan") ];
            }

            $pinvest = IvInvest::where('status', InvestmentStatus::PENDING)->get();
            if (!blank($pinvest)) {
                $facts[] = [ 'num' => $pinvest->count(), 'text' => __("pending investment plan") ];
            }

            $dtnx = Transaction::where('type', TransactionType::DEPOSIT)->where('status', TransactionStatus::PENDING)->get();
            if (!blank($dtnx)) {
                $facts[] = [ 'num' => $dtnx->count(), 'text' => __("pending deposits transaction") ];
            }

            $wtnx = Transaction::where('type', TransactionType::WITHDRAW)->where('status', TransactionStatus::PENDING)->get();
            if (!blank($wtnx)) {
                $facts[] = [ 'num' => $wtnx->count(), 'text' => __("pending withdrawls transaction") ];
            }

            return $facts;
        });

        if (!blank($fun_facts)) {
            $output = Arr::shuffle($fun_facts);
        }
        return $output[0] ?? [];
    }
}

if (!function_exists('to_dfp')) {
    /**
     * @param $cur_num | number
     * @param $old_num | number
     * @since 1.0
     * @version 1.0.0
     * @return bool
     */
    function to_dfp($cur_num, $old_num)
    {
        if ($cur_num == $old_num) {
            return 0;
        }

        if ($cur_num == 0 && $old_num > 0) {
            return -100;
        }
        if ($cur_num > 0 && $old_num == 0) {
            return 100;
        }

        $num1 = BigDecimal::of($cur_num);
        $num2 = BigDecimal::of($old_num);
        $percent = $num1->minus($num2)->dividedBy($num1, 2, RoundingMode::CEILING)->multipliedBy(100);

        return is_object($percent) ? (string)$percent : $percent;
    }
}

if (!function_exists('get_live_rate')) {
    /**
     * @param $from
     * @param $to
     * @param $amount
     * @return string
     * @version 1.0.0
     * @since 1.2.0
     */
    function get_live_rate($from, $to, $amount = 1)
    {
        $from = key_to_currency($from);
        $to = key_to_currency($to);
        ;

        $rates = array_merge(get_automatic_rates(null), [base_currency() => 1]);
        $decimal = is_crypto($to) ? dp_calc('crypto') : dp_calc('fiat');

        if (isset($rates[$from]) && isset($rates[$to])) {
            $amount = calc_rate($rates[$from], $rates[$to], $amount);
            return round($amount, $decimal);
        }

        return 0.0;
    }
}

if (!function_exists('preview_media')) {
    /**
     * @param $path
     * @since 1.0
     * @version 1.0.0
     * @return mixed
     */
    function preview_media($path)
    {
        if (Storage::has($path)) {
            return 'data:image/jpeg;base64,'.base64_encode(Storage::get($path));
        }
        return '';
    }
}

if (!function_exists('time_zone')) {
    /**
     * @since 1.0
     * @version 1.0.0
     * @return mixed
     */
    function time_zone()
    {
        return !empty(gss('time_zone')) ? gss('time_zone') : 'UTC';
    }
}

if (!function_exists('filter_count')) {
    /**
     * @param $str
     * @since 1.0
     * @version 1.0.0
     * @return mixed
     */
    function filter_count()
    {
        return count(array_filter(request()->query(), function ($val, $key) {
            return $val !== 'any' && $key !== 'filter';
        }, ARRAY_FILTER_USE_BOTH));
    }
}

if (!function_exists('strip_tags_map')) {
    /**
     * @param $str
     * @since 1.0
     * @version 1.0.0
     * @return mixed
     */
    function strip_tags_map($str)
    {
        return ($str && !is_array($str)) ? strip_tags($str) : $str;
    }
}

if (!function_exists('dark_theme')) {
    /**
     * @param $state
     * @return mixed
     * @version 1.0.0
     * @since 1.2.0
     */
    function dark_theme($state = false)
    {
        $output = false;
        if (module_exist('NioExtend', 'addon')) {
            $dark = app()->get('nioextend')->dark;
            if (!blank($dark)) {
                $output = $dark->setState($state)->getOutput();
            }
        }
        return $output;
    }
}

if (!function_exists('is_locked')) {
    /**
     * @param $type
     * @param $prefix
     * @since 1.1.0
     * @version 1.1.1
     * @return boolean
     */
    function is_locked($type, $prefix = '', $time = 5)
    {
        $key = (!empty($prefix)) ? $prefix : 'payout_locked';
        $gss = ($key) ? gss($key . '_' . $type) : gss($type);

        if ($gss == null) {
            return false;
        } elseif (!empty($gss)) {
            return !now()->greaterThan(Carbon::parse($gss)->addMinutes($time));
        }

        return false;
    }
}

if (!function_exists('kyc_enabled')) {
    /**
     * @version 1.0
     * @since 1.1.2
     * @return boolean
     */
    function kyc_enabled()
    {
        return (module_exist('BasicKYC', 'mod') && feature_enable('kyc')) ? true : false;
    }
}

if (!function_exists('kyc_required')) {
    /**
     * @param $type
     * @param $user|bool
     * @version 1.1
     * @since 1.1.2
     * @return boolean
     */
    function kyc_required($type = '', $user = false)
    {
        $isRequired = (kyc_enabled() && data_get(gss('kyc_verified', []), $type)) ? true : false;

        if ($user === true) {
            $authUser = auth()->user();

            return ($isRequired && !$authUser->kyc_verified) ? true : false;
        }
        return $isRequired;
    }
}

if (!function_exists('profile_lockable')) {
    /**
     * @version 1.0
     * @since 1.1.2
     * @return boolean
     */
    function profile_lockable()
    {
        if (auth()->check()) {
            return ((auth()->user()->kyc_verified || auth()->user()->kyc_pending) && gss('kyc_locked_profile', 'yes') == 'yes') ? true : false;
        }

        return false;
    }
}

if (!function_exists('social_auth')) {
    /**
     * @param $platform
     * @version 1.0
     * @since 1.1.2
     * @return boolean
     */
    function social_auth($platform = null)
    {
        $social = gss('social_auth', 'off') == 'on' ? true : false;

        if (!empty($platform)) {
            $valid_keys = (!empty(gss('social_' . $platform . '_id')) && !empty(gss('social_' . $platform . '_secret'))) ? true : false;
            return $social && $valid_keys;
        }

        $fb_keys = (!empty(gss('social_facebook_id', '')) && !empty(gss('social_facebook_secret', ''))) ? true : false;
        $ggle_keys = (!empty(gss('social_google_id', '')) && !empty(gss('social_google_secret', ''))) ? true : false;

        return $social && ($fb_keys || $ggle_keys);
    }
}

if (!function_exists('hide_email')) {

    /**
     * @param $email
     * @version 1.0
     * @since 1.1.2
     * @return string
     */
    function hide_email($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            list($first, $last) = explode('@', $email);
            $first = str_replace(substr($first, '3'), str_repeat('*', strlen($first) - 3), $first);
            $last = explode('.', $last);
            $last_domain = str_replace(substr($last['0'], '1'), str_repeat('*', strlen($last['0']) - 1), $last['0']);
            $hideEmailAddress = $first . '@' . $last_domain . '.' . $last['1'];
            return $hideEmailAddress;
        }
        return '';
    }
}

if (!function_exists('replace_shortcut')) {
    /**
     * @param $content|string
     * @version 1.0
     * @since 1.1.2
     * @return string
     */
    function replace_shortcut($content)
    {
        $shortcuts = [
            '[[privacy]]' => get_page_link('policy'),
            '[[terms]]' => get_page_link('terms')
        ];
        $content = strtr($content, $shortcuts);

        return $content;
    }
}

if (!function_exists('user_consent')) {
    /**
     * @version 1.0
     * @since 1.1.2
     * @return boolean
     */
    function user_consent()
    {
        if (sys_settings('gdpr_enable', 'no') == 'no') {
            return false;
        }

        $consent = request()->cookie('_pconsent_' . ghp());

        if (in_array($consent, ['yes', 'no'])) {
            return $consent;
        }

        return null;
    }
}

if (!function_exists('send_money_allowed')) {
    /**
     * @version 1.0.0
     * @since 1.1.4
     */
    function send_money_allowed()
    {
        $transfer = feature_enable('transfer');

        if ($transfer) {
            return (sys_settings('transfer_disable_request', 'no') == 'no') ? true : false;
        }

        return false;
    }
}


if (!function_exists('send_money_amount')) {
    /**
     * @param $type
     * @version 1.0.0
     * @since 1.1.4
     */
    function send_money_amount($type = null)
    {
        $type = in_array($type, ['min', 'max']) ? $type : 'min';
        $default = 0;

        if ($type == 'max') {
            $amnt = (float) sys_settings('transfer_maximum_amount', 0);
        } else {
            $amnt = (float) sys_settings('transfer_minimum_amount', 0);
            $default = min_to_compare();
        }

        $amount = (BigDecimal::of($amnt)->compareTo(0) == 1) ? BigDecimal::of($amnt) :  BigDecimal::of($default);

        return is_object($amount) ? (string) $amount : $amount;
    }
}


if (!function_exists("filtered_countries")) {
    /**
     * @param object $method
     * @return array
     * @version 1.0.0
     * @since 1.1.3
     */
    function filtered_countries($method = null)
    {
        if ($method && (!$method instanceof WithdrawMethod && !$method instanceof PaymentMethod)) {
            return filtered_countries();
        }

        $key = $method ? data_get($method, 'slug') . '_filtered_countries' : 'filtered_countries';

        $restriction = $method ? data_get($method, 'config.country_restriction_type', 'disable') : sys_settings('country_restriction_type', 'disable');

        if ($method && $restriction == 'global') {
            return Cache::get('fitered_countries') ?? filtered_countries();
        }

        $countries = $method ? data_get($method, 'countries', []) : sys_settings('countries', []);

        if (empty($countries)) {
            return config('countries');
        }

        return Cache::remember($key, 1800, function () use ($countries, $restriction) {
            switch ($restriction) {
                case 'include':
                    $filteredCountries = array_intersect_key(config('countries'), array_flip($countries));
                    break;
                case 'exclude':
                    $filteredCountries = array_diff_key(config('countries'), array_flip($countries));
                    break;
                default:
                    $filteredCountries = config('countries');
                    break;
            }
            return $filteredCountries ?? config('countries');
        });
    }
}

if (!function_exists('get_fs_tip')) {
    /**
     * @return mixed
     * @version 1.0.0
     * @since 1.1.4
     * @return mixed
     */
    function get_fs_tip($payment)
    {
        $tip = ['fees' => 0];
        if (module_exist('NioExtend', 'addon')) {
            $fees = app()->get('nioextend')->fees;
            if (filled($fees)) {
                $tip = $fees->getFeeToolTip($payment);
            }
        }
        return $tip;
    }
}

if (!function_exists('contains_only_zero')) {
    /**
     * @return bool
     * @version 1.0.0
     * @since 1.1.4
     */
    function contains_only_zero(array $array): bool
    {
        foreach ($array as $value) {
            if ($value != 0) {
                return false;
            }
        }
        return true;
    }
}

if (!function_exists('address_lines')) {
    /**
     * @param $data arrary
     * @param $extra
     * @version 1.0.0
     * @since 1.2.0
     */
    function address_lines(array $data, $extra = false)
    {
        $address = [];

        if (the_data($data, 'address_line_1')) {
            $address[0] = the_data($data, 'address_line_1');
        }

        if (the_data($data, 'address_line_2')) {
            $address[1] = the_data($data, 'address_line_2');
        }

        if (the_data($data, 'city')) {
            $address[2] = the_data($data, 'city');
        }

        $state = (the_data($data, 'state')) ? the_data($data, 'state') : '';
        $zipcode = (the_data($data, 'zip')) ? the_data($data, 'zip') : '';

        if ($state || $zipcode) {
            $hyphen = ($state && $zipcode) ? ' - ' : '';
            $address[3] = $state . $hyphen . $zipcode;
        }

        if ($extra && the_data($data, 'country')) {
            $address[4] = the_data($data, 'country');
        }

        $output = (!empty($address)) ? implode(', ', $address) : false;

        return ($output) ? $output : '';
    }
}

if (!function_exists('short_to_full')) {
    /**
     * @return $name
     * @version 1.0.0
     * @since 1.1.5
     */
    function short_to_full($name)
    {
        $name = strtolower($name);
        $all_abrv =  array(
            'bep2' => 'BC Chain',
            'bep20' => 'BSC Chain',
            'bsc' => 'BSC Chain',
            'erc20' => 'ERC20',
            'trc20' => 'TRC20',
        );
        $return = (isset($all_abrv[$name]) ? $all_abrv[$name] : '');
        return $return;
    }
}

if (!function_exists('short_to_docs')) {
    /**
     * @return $short
     * @version 1.0.0
     * @since 1.2.0
     */
    function short_to_docs($short, $fallback = '')
    {
        $short = strtolower($short);
        $documents =  array(
            'pp' => 'Passport',
            'nid' => 'National ID',
            'dvl' => 'Driving License',
            'bs' => 'Bank Statement',
            'ub' => 'Utility Bill',
            'proof' => 'Selfie with Document',
        );
        $return = (isset($documents[$short]) ? $documents[$short] : $fallback);
        return $return;
    }
}

if (!function_exists('trans_replace')) {
    /**
     * @return $text
     * @version 1.0.0
     * @since 1.1.5
     */
    function trans_replace($text)
    {
        $find = [
            'Invest on',
            'Deposit via',
            'Withdraw via',
            'Received from',
            'Crypto Wallet',
            'Bank Transfer',
            'Wire Transfer',
            'Investment Account',
        ];

        $trans = [
            __('Invest on'),
            __('Deposit via'),
            __('Withdraw via'),
            __('Received from'),
            __('Crypto Wallet'),
            __('Bank Transfer'),
            __('Wire Transfer'),
            __('Investment Account'),
        ];

        return str_replace($find, $trans, $text);
    }
}

if (!function_exists('get_ext_currencies')) {
    /**
     * @param $type
     * @return mixed
     * @version 1.0.0
     * @since 1.3.0
     */
    function get_ext_currencies()
    {
        if (module_exist('ExtCurrency', 'addon')) {
            $currencies = app()->get('extcurrency')->currencies('system');
            if (blank($currencies)) {
                return collect([]);
            }
            return collect($currencies)->keyBy("code");
        }

        return collect([]);
    }
}

if (!function_exists('get_custom_currencies')) {
    /**
     * @param $type
     * @return mixed
     * @version 1.0.0
     * @since 1.3.0
     */
    function get_custom_currencies()
    {
        if (module_exist('ExtCurrency', 'addon')) {
            $currencies = app()->get('extcurrency')->currencies('custom');
            if (blank($currencies)) {
                return [];
            }
            return $currencies;
        }

        return [];
    }
}
