<?php


namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Referral;

use App\Enums\TransactionType;
use App\Enums\TransactionStatus;

use App\Http\Controllers\Controller;
class ReferralController extends Controller
{
    public function index()
    {
    	if(!referral_system()) {
	    	return redirect()->route('dashboard')->withErrors(['warning' => __('Sorry, the page you are looking for could not be found.')]);
    	}

        $refers = Referral::with('referred')->where('refer_by', auth()->user()->id)->paginate(10, ['*'], 'refers');
        $transactionsQuery = Transaction::where('type', TransactionType::REFERRAL)->where('user_id', auth()->user()->id)->orderBy('id', 'desc');
        $bonusReceivedCollection = $transactionsQuery->get()->where('status', TransactionStatus::COMPLETED);
        $earnings = $bonusReceivedCollection->mapToGroups(function ($item, $key) {
            return [data_get($item->meta, 'referral.user') => $item->amount];
        })->all();
        $bonusReceived = $bonusReceivedCollection->sum('amount');
        $bonusPending = $transactionsQuery->get()->where('status', TransactionStatus::PENDING)->sum('amount');
        $stats = [
            'refer' => $refers->total(),
            'received' => $bonusReceived,
            'pending' => $bonusPending,
        ];
        $transactions = $transactionsQuery->paginate(user_meta('tnx_perpage', 10), ['*'], 'transactions')->onEachSide(0);
        
        return view('user.referrals.index', compact('stats', 'transactions', 'refers', 'earnings'));
    }
}
