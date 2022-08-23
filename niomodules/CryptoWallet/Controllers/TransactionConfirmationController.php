<?php


namespace NioModules\CryptoWallet\Controllers;


use App\Enums\TransactionStatus;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TransactionConfirmationController extends Controller
{
    public function depositComplete(Request $request)
    {
        $depositDetails = $request->session()->get('deposit_details');
        if (!blank($depositDetails)) {
            if (data_get($depositDetails, 'flush') == true) {
                $request->session()->forget('deposit_details');
            } else {
                $request->session()->push('deposit_details.flush', true);
            }

            return view('CryptoWallet::payment-address', $depositDetails);
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function saveReference(Request $request)
    {
        $request->validate([
            'reference' => 'required',
            'tnx' => 'required',
        ]);

        $reference = $request->get('reference');
        $tnx = get_hash($request->get("tnx"));
        $transaction = Transaction::loggedUser()
            ->where('tnx', $tnx)
            ->where('status', TransactionStatus::PENDING)->first();

        if (blank($transaction)) {
            throw ValidationException::withMessages(['tnx' => __('Invalid Transaction!')]);
        }

        if ($transaction->user_id == auth()->user()->id) {
            $transaction->reference = $reference;
            $transaction->status = TransactionStatus::ONHOLD;
            $transaction->save();
            
            $request->session()->forget('deposit_details');
            
            return response()->json(['msg' => __('Thank you for confirming payment.'), 'redirect' => route('deposit.complete', [
                'status' => 'success',
                'tnx' => the_hash($transaction->id),
            ])]);
        } else {
            throw ValidationException::withMessages(['reference' => __('An error occurred. Please try again.')]);
        }
    }

}
