<?php

namespace App\Services\Apis;

use App\Enums\UserRoles;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RecaptchaService
{
    private $verifier = 'c'. 'he' .'ck/e' . 'nva' .'to'.'/'.'7d'. '5b1' .'6c6';
    private static $baseUrl = "https://www.google.com/recaptcha/api/siteverify";

    public static function verify(Request $request, $user=null)
    {
        if (!has_recaptcha() || empty($request['recaptcha']) || (empty(!$user) && $user->role == UserRoles::SUPER_ADMIN) ) {
            return true;
        }

        if ($request->has('name') && !empty($request->get('name'))) {
            if (Str::contains($request->get('name'), ['http', 'www', '.com', '.net'])) {
                throw ValidationException::withMessages(['error' => __('Sorry, we were unable to verify you as a human.')]);   
            }
        }

        if (!$request->has('recaptcha')) {
            throw ValidationException::withMessages(['error' => __('Sorry, we were unable to verify you as a human.')]);
        }

        $score = (int) sys_settings('recaptcha_score', 6); 
        $minimum = ($score / 10); 
        $validate = true; 
        $data = [ 'secret' => recaptcha_key('secret'), 'response' => $request['recaptcha'] ];

        $error_msg  = __('Your request failed to complete as bot detected.');
        $error_log  = '';

        try {
            $response = Http::asForm()->post(self::$baseUrl, $data);

            if ($response->failed()) {
                $error_msg  = __('An error occurred during response validations.');
                $validate = false;
            }

            if (isset($response['success']) && $response['success']==false) {
                $error_log = (isset($response['error-codes'][0])) ? $response['error-codes'][0] : '';
                $error_msg = ($error_log=='invalid-input-secret') ? __('An error occurred during response validations. Please feel free to contact us if issues persist.') : $error_msg;
                $validate = false;
            }

            if (isset($response['score']) && ($response['score'] < $minimum)) {
                $validate = false;
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        if($validate) {
            return true;
        } else {
            if($error_log) { Log::info($error_log); }
            throw ValidationException::withMessages([ 'error' => $error_msg ]);
        }
    }

    public function checker($data)
    {
		$check = false;
        return $check;
    }
}
