<?php


namespace App\Http\Controllers\User;

use App\Enums\WithdrawMethodStatus;
use App\Http\Controllers\Controller;
use App\Models\UserAccount;
use App\Models\WithdrawMethod;

class WithdrawAccountController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function view()
    {
        $accounts = UserAccount::where('user_id', auth()->user()->id)->get();
        $wdMethods = WithdrawMethod::whereIn('slug', array_column(available_withdraw_methods(), 'slug'))
            ->where('status', WithdrawMethodStatus::ACTIVE)
            ->get()->filter(function ($item) {
                return filled($item->is_active);
            })->keyBy('slug');

        return view('user.account.withdraw-account', compact('accounts', 'wdMethods'));
    }
}
