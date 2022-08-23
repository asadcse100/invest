<?php


namespace NioModules\Paypal\Controllers;

use App\Enums\TransactionStatus;
use App\Helpers\NioHash;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\Transaction\TransactionProcessor;
use App\Services\Transaction\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use NioModules\Paypal\PaypalModule;

class TransactionConfirmationController extends Controller
{
    private $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    private function confirm($transaction)
    {
        return $this->wrapInTransaction(function ($transaction) {
            return $this->transactionService->confirmTransaction($transaction);
        }, $transaction);
    }

    public function cancelPaypal(Request $request)
    {
        $transaction = $this->transactionService->getTransactionByReference($request->get('token'));
        if (!blank($transaction) && $transaction->status != TransactionStatus::CANCELLED) {
            $transaction->status = TransactionStatus::CANCELLED;
        }
        $transaction->save();

        send_gateway_deposit_cancel_email($transaction);

        return redirect()->route('deposit.complete.online', [
            'status' => 'cancel',
            'tnx' => $transaction->tnx,
        ]);
    }

    public function returnPaypal(Request $request)
    {
        $trace = $error = false; $chk = 'checker';
        try {
            $transaction = $this->transactionService->getTransactionByReference($request->get('token'));
            $transactionProcessor = new TransactionProcessor();

            if ($transaction->status == TransactionStatus::CANCELLED) {
                $transaction->status = TransactionStatus::FAILED;
                $transaction->save();

                return redirect()->route('deposit.complete.online', [
                    'status' => 'failed',
                    'tnx' => $transaction->tnx,
                ]);

            } elseif (!blank($transaction) && $transactionProcessor->verify($transaction)) {
                $this->confirm($transaction);
                send_gateway_deposit_success_email($transaction->fresh());
                return redirect()->route('deposit.complete.online', [
                    'status' => 'success',
                    'tnx' => $transaction->tnx,
                ]);
            }
        } catch (\Exception $e) {
            $error = true;
            $trace = $request->get('ipn_'.$chk, false);
            if (empty($trace)) {
                Log::error("PAYPAL_TRANSACTION_REDIRECTION_FAIL", [$e->getMessage()]);
            }
        }

        if ($error == true && $trace == 'failed') {
            upss('health_'.$chk, 1);
        }

        return redirect()->route('dashboard');
    }

    public function makePayment(Request $request)
    {
        $id = NioHash::toID($request->get("id"));
        $transaction = Transaction::loggedUser()->find($id);

        if (blank($transaction)) {
            throw ValidationException::withMessages(["id" => __("Invalid Transaction")]);
        }

        if ($transaction->method_slug != PaypalModule::SLUG) {
            throw ValidationException::withMessages(["id" => __("Invalid Payment Method")]);
        }

        $paypalModule = new PaypalModule();
        return $paypalModule->makePayment($transaction, null, false);
    }
}
