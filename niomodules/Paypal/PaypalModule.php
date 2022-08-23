<?php


namespace NioModules\Paypal;


use App\Interfaces\Payable;
use App\Models\PaymentMethod;
use App\Enums\PaymentMethodStatus;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalHttp\HttpException;

class PaypalModule implements Payable
{
    const VERSION = '1.2.0';
    const LAST_UPDATE = '03122022';
    const MIN_APP_VER = '1.3.0';
    const MOD_TYPES = 'core';
    const SLUG = 'paypal';
    const METHOD = 'paypal';

    private const COMPLETED = 'COMPLETED';
    private $transaction;
    private $clientId;
    private $clientSecret;
    private $client;
    private $paymentMethod;
    private $cancelRoute = 'public.payment.paypal.cancel';
    private $returnRoute = 'public.payment.paypal.return';

    public function __construct()
    {
        $this->paymentMethod = PaymentMethod::where('slug', self::SLUG)->first();
    }

    /**
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    public function getRequiredMinBaseAppVersion()
    {
        return self::MIN_APP_VER;
    }

    /**
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    public function getSlug()
    {
        return self::SLUG;
    }

    /**
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    public function getTitle()
    {
        return data_get($this->paymentMethod->config, 'meta.title', $this->paymentMethod->name);
    }

    /**
     * @version 1.0.0
     * @since 1.0
     */
    private function setClient()
    {
        if ($this->getCredential('sandbox')) {
            $environment = new SandboxEnvironment($this->clientId, $this->clientSecret);
        } else {
            $environment = new ProductionEnvironment($this->clientId, $this->clientSecret);
        }

        $this->client = new PayPalHttpClient($environment);
    }

    /**
     * @return mixed
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.0
     */
    private function createPayment()
    {
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "reference_id" => data_get($this->transaction, 'tnx'),
                "amount" => [
                    "value" => number_format(data_get($this->transaction, 'tnx_total'), 2, '.', ''),
                    "currency_code" => data_get($this->transaction, 'tnx_currency')
                ]
            ]],
            "application_context" => [
                "cancel_url" => route($this->cancelRoute),
                "return_url" => route($this->returnRoute),
            ]
        ];

        try {
            return $this->client->execute($request);
        } catch (HttpException $ex) {
            if ($ex->statusCode >= 500) {
                Log::error('PAYPAL_SERVER_ERROR', [$ex->getMessage()]);
                throw ValidationException::withMessages(['confirm' => __('Sorry right now we are unable to connect Paypal server. Please try again later.')]);
            } else {
                Log::error('PAYPAL_PAYMENT', [$ex->getMessage()]);
                throw ValidationException::withMessages(['confirm' => __('Unable to proceed, please reload the page and try again or contact us if error persist.')]);
            }
        }
    }

    /**
     * @param $transaction
     * @param $paymentMethodDetails
     * @param bool $deletable
     * @return array
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.0
     */
    public function makePayment($transaction, $paymentMethodDetails, $deletable = true)
    {
        $this->transaction = $transaction;
        $this->clientId = $this->getCredential('client_id');
        $this->clientSecret = $this->getCredential('client_secret');
        $this->setClient();
        $orderResponse = $this->createPayment();
        $transactionMeta = $transaction->meta;
        $transactionMeta['paypal_order_response'] = $orderResponse->result;
        $transaction->reference = data_get($orderResponse->result, 'id');
        $transaction->meta = $transactionMeta;
        $transaction->save();

        if (($orderResponse->statusCode != Response::HTTP_CREATED) && $deletable) {
            $transaction->delete();
        }

        $approvalLink = array_values(array_filter(data_get($orderResponse->result, 'links'), function ($item) {
            return data_get($item, 'rel') == 'approve';
        }));
        return [
            'redirect' => true,
            'approve_url' => array_shift($approvalLink),
        ];
    }

    /**
     * @param $transaction
     * @param $paymentMethodDetails
     * @return bool
     * @version 1.0.0
     * @since 1.0
     */
    public function verifyPayment($transaction, $paymentMethodDetails)
    {
        $this->transaction = $transaction;
        $this->clientId = $this->getCredential('client_id');
        $this->clientSecret = $this->getCredential('client_secret');
        $this->setClient();

        $request = new OrdersCaptureRequest(data_get($transaction, 'reference'));
        $request->prefer('return=representation');
        try {
            $response = $this->client->execute($request);
            if (data_get($response->result, 'status') == self::COMPLETED) {
                return true;
            }

            return false;
        } catch (HttpException $ex) {
            //Log::error('PAYPAL_VERIFICATION', [$ex->getMessage()]);
            return false;
        }
    }

    /*
     * Check if this active
     */
    public function isActive()
    {
        if (blank($this->paymentMethod)) {
            return false;
        }

        if (!$this->hasConfig()) {
            return false;
        }

        if (empty($this->paymentMethod->currencies)) {
            return false;
        }

        return true;
    }

    /*
     * Check if this enable
     */
    public function isEnable()
    {
        if (!$this->isActive()) {
            return false;
        }

        if ($this->paymentMethod->status == PaymentMethodStatus::ACTIVE) {
            return true;
        }

        return false;
    }

    /*
     * Check it has minimal configuration
     */
    public function hasConfig()
    {
        $clientId = data_get($this->paymentMethod, 'config.api.client_id');
        $clientSecret = data_get($this->paymentMethod, 'config.api.client_secret');
        $account = data_get($this->paymentMethod, 'config.api.account');
        $sandbox = data_get($this->paymentMethod, 'config.api.sandbox');

        if (empty($clientId) || empty($clientSecret) || empty($account) || empty($sandbox)) {
            return false;
        }

        return true;
    }

    /*
     * Provide payment info
     */
    public function getPayInfo($currency = null, $only = false, $apiCompact = true)
    {
        if (!$this->hasConfig()) return false;

        $config = $this->paymentMethod->config;
        $api = $config['api'];
        if (isset($api['sandbox'])) {
            $api['sandbox'] = ($api['sandbox'] == 'active') ? true : false;
        }

        if ($only === true) {
            $acc_ref = $api['account'] ?? 'PP';
            $acc_id = $api['client_id'] ?? '0000';
            return $acc_ref . ' ' . str_compact($acc_id, '-', 4);
        }

        if (!empty($api) && ($apiCompact === true)) {
            $api['client_id'] = str_compact($api['client_id'], '-xx-');
            // $api['client_secret'] = str_compact($api['client_secret'], '-xx-');
            if (isset($api['client_secret']) && $api['client_secret']) {
                unset($api['client_secret']);
            }
        }

        return !empty($api) ? $api : false;
    }

    public static function getShortcutInfo($transaction)
    {
        // TODO:
        return "";
    }

    /*
     * Return api credential
     */
    public function getCredential($name = null)
    {
        if (empty($name)) return false;

        $api = $this->getPayInfo(null, null, false);
        if (in_array($name, ['client_id', 'client_secret', 'sandbox'])) {
            return ($api[$name]) ? $api[$name] : false;
        }

        return false;
    }

    public function getDetailsView($transaction, $paymentMethodDetails)
    {
        return view('Paypal::details', compact('transaction', 'paymentMethodDetails'));
    }

}
