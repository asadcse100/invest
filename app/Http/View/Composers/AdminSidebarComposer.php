<?php

namespace App\Http\View\Composers;

use App\Enums\UserRoles;
use App\Enums\TransactionType;
use App\Enums\TransactionStatus;

use App\Models\User;
use App\Models\Transaction;

use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AdminSidebarComposer
{
    /**
     * @var User
     */
    private $user;
    /**
     * @var Transaction
     */
    private $transaction;

    public function __construct(User $user, Transaction $transaction)
    {
        $this->user = $user;
        $this->transaction = $transaction;
    }

    public function compose(View $view)
    {
        $userCountByStatus = $this->user->select('status', DB::raw('count(*) as total'))
            ->whereNotIn('role', [UserRoles::ADMIN, UserRoles::SUPER_ADMIN])
            ->groupBy('status')
            ->get()->pluck('total', 'status');

        $adminUserCount = $this->user->whereIn('role', [UserRoles::ADMIN, UserRoles::SUPER_ADMIN])->count();

        $pendingTransactions = $this->transaction->where('status', TransactionStatus::PENDING)->get();
        $onholdTransactions = $this->transaction->where('status', TransactionStatus::ONHOLD)->get();
        $confirmedTransactions = $this->transaction->where('status', TransactionStatus::CONFIRMED)->get();
        $pendingDepositCount = $pendingTransactions->where('type', TransactionType::DEPOSIT)->count();
        $pendingWithdrawCount = $pendingTransactions->where('type', TransactionType::WITHDRAW)->count();
        $pendingReferralCount = $pendingTransactions->where('type', TransactionType::REFERRAL)->count();

        $view->with([
            'userCount' => $userCountByStatus,
            'adminUserCount' => $adminUserCount,
            'pendingTransactionCount' => $pendingTransactions->whereNotIn('type', [TransactionType::REFERRAL])->count(),
            'onholdTransactionCount' => $onholdTransactions->count(),
            'confirmedTransactionCount' => $confirmedTransactions->count(),
            'pendingDepositCount' => $pendingDepositCount,
            'pendingWithdrawCount' => $pendingWithdrawCount,
            'pendingReferralCount' => $pendingReferralCount
        ]);
    }
}
