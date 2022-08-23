<?php


namespace App\Services\Apis\ExchangeRate;

use App\Models\Setting;
use App\Services\Apis\BaseAPi;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ExchangeRateApi extends BaseAPi
{
    private $baseUrl = 'https://data.exratesapi.com/rates';
    private $timeOut = 60;

    /**
     * @return array
     * @version 1.0.0
     * @since 1.0
     */
    private function currencies()
    {
        $allowedCurrencies = array_intersect(get_currencies(true, '', true), active_currencies('key', '', true));
        return array_diff($allowedCurrencies, [sys_settings('base_currency')]);
    }

    /**
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    private function getApiUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    private function getApiKey()
    {
        $apikey = (!empty(sys_settings('exratesapi_access_key'))) ? sys_settings('exratesapi_access_key') : get_ex_apikey();

        return ($apikey) ? ['access_key' => $apikey, 'app' => sys_info('key'), 'ver' => sys_info('version')] : [];
    }

    /**
     * @return string
     * @version 1.0.1
     * @since 1.0
     */
    private function getApiData()
    {
        $data = [];
        $apikey = $this->getApiKey();

        if (!empty($apikey)) {
            $data = [
                'valid' => get_path(),
                'base' => sys_settings('base_currency'),
                'currencies' => implode(',', $this->currencies()),
            ];
        }

        return ($apikey && $data) ? array_merge($apikey, $data) : [];
    }

    private function serviceTimeout($type = null)
    {
        $output = $this->timeOut * 10;

        if (!empty($type)) {
            $service = ($type == 'invalid_api_key') ? 'no' : 'na';
            Setting::updateOrCreate(['key' => 'api_service'], ['value' => $service]);
            $output = $this->timeOut * 60;
        }

        return $output;
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @version 1.0.0
     * @since 1.0
     */
    private function getLatestRates()
    {
        $rates = [];
        $scheduler = (Cache::has('exrates_scheduler')) ? Cache::get('exrates_scheduler') : false;
        if (serverOpenOrNot($this->baseUrl) && !empty($this->getApiData()) && empty($scheduler)) {
            $http = Http::withHeaders(['X-Api-Signature' => base64_encode(get_path())]);
            try {
                $response = $http->get($this->getApiUrl(), $this->getApiData());

                if ($response->successful()) {
                    if ( $response->json('success') == true && !empty($response->json('rates')) && is_array($response->json('rates')) ) {
                        $rates = $response->json('rates');
                        Setting::updateOrCreate(['key' => 'automatic_exchange_rate'], ['value' => json_encode($rates)]);
                        Setting::updateOrCreate(['key' => 'exchange_last_update'], ['value' => time()]);
                        Setting::updateOrCreate(['key' => 'exratesapi_error_msg'], ['value' => '']);
                        Setting::updateOrCreate(['key' => 'api_service'], ['value' => 'yes']);
                        Cache::forget('exrates_scheduler');
                    } else {
                        $timeout = $this->serviceTimeout($response->json('error.type'));
                        $message = ($response->json('error.message')) ? $response->json('error.message') : 'Unable to fetch live rates from ExRateApi.com';
                        Log::error('exratesapi-error', [$message]); Cache::put('exrates_scheduler', (time() + $timeout), $timeout);
                        Setting::updateOrCreate(['key' => 'exratesapi_error_msg'], ['value' => $message]);
                    }
                } else {
                    $response->throw();
                }
            } catch (\Exception $e) {
                Log::error('exratesapi-error', [$e->getMessage()]);
                Setting::updateOrCreate(['key' => 'exratesapi_error_msg'], ['value' => 'Occurred unknown error in server or client side.']);
            }
        } else {
            Setting::updateOrCreate(['key' => 'exratesapi_error_msg'], ['value' => 'Access key was not sepecified in application.' ]);
        }
        if(empty($rates)) {
            $rates = sys_settings('automatic_exchange_rate');
        }

        return $rates;
    }

    public function refreshCache($force=false)
    {
        Cache::forget('exchange_rates');
        return $this->getExchangeRates($force);
    }

    /**
     * @param $force | boolean
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    public function getExchangeRates($force=false)
    {
        if($force===true) {
            return $this->getLatestRates();
        }

        return Cache::remember('exchange_rates', (sys_settings('exchange_auto_update', 30) * $this->timeOut), function(){
            return $this->getLatestRates();
        });
    }
}
