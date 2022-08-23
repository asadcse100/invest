<?php


namespace NioModules\Bank\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

            return view('Bank::deposit-complete', $depositDetails);
        } else {
            return redirect()->route('dashboard');
        }
    }
}
