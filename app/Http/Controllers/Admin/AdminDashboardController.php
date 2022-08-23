<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Enums\UserRoles;
use App\Enums\UserStatus;
use App\Models\User;
use App\Models\IvInvest;
use App\Models\Transaction;
use App\Services\GraphData;
use App\Updates\UpdateManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index(Request $request, GraphData $graph, UpdateManager $updateManager)
    {
        if ($updateManager->hsaPendingMigration() || $updateManager->isUpdateAvailable()) {
            return redirect()->route('admin.update.systems');
        }

        $dailyInsights = $request->has('days') ? $this->dailyInsights($graph, $request->days) : $this->dailyInsights($graph);

        $transactions   = $this->transactions();
        $deposit        = $this->deposit();
        $withdraw       = $this->withdraw();
        $userActivity   = $this->userActivities($graph);

        $pending = Transaction::where('status', TransactionStatus::PENDING)
            ->selectRaw('count(*) as total, type')
            ->groupby('type')->get();

        return view('admin.dashboard', [
            'dailyInsights' => $dailyInsights,
            'transactions' => $transactions,
            'deposit' => $deposit,
            'withdraw' => $withdraw,
            'pending' => $pending,
            'stats' => $userActivity,
        ]);
    }

    private function dailyInsights(GraphData $graph, $days = 15)
    {
        $graph->set('amount', 'completed_at');

        $deposit = Transaction::deposit()
            ->completed()
            ->whereBetween('completed_at', [
                Carbon::now()->subDays($days)->startOfDay()->tz(time_zone()),
                Carbon::now()->tz(time_zone())
            ])
            ->sumAmount()
            ->byCompleted()
            ->get()
            ->toArray();

        $withdraw = Transaction::withdraw()->completed()
            ->whereBetween('completed_at', [
                Carbon::now()->subDays($days)->startOfDay()->tz(time_zone()),
                Carbon::now()->tz(time_zone())
            ])
            ->sumAmount()
            ->byCompleted()
            ->get()
            ->toArray();

        return [
            'deposit' => $graph->getDays($deposit, $days)->get(),
            'withdraw' => $graph->getDays($withdraw, $days)->get()
        ];
    }


    public function deposit()
    {
        $total = Transaction::deposit()
            ->completed()
            ->sumAmount()
            ->first()
            ->toArray()['amount'];

        $thisWeek = Transaction::deposit()
            ->completed()
            ->sumAmount()
            ->thisWeek()
            ->first()
            ->toArray()['amount'];

        $lastWeek = Transaction::deposit()
            ->completed()
            ->sumAmount()
            ->lastWeek()
            ->first()
            ->toArray()['amount'];

        $thisMonth = Transaction::deposit()
            ->completed()
            ->sumAmount()
            ->thisMonth()
            ->first()
            ->toArray()['amount'];

        $lastMonth = Transaction::deposit()
            ->completed()
            ->sumAmount()
            ->lastMonth()
            ->first()
            ->toArray()['amount'];

        return [
            'total' => $total,
            'this_week' => $thisWeek,
            'this_month' => $thisMonth,
            'last_month' => $lastMonth,
            'prtc_weekly' => to_dfp($thisWeek, $lastWeek),
            'prtc_monthly' => to_dfp($thisMonth, $lastMonth)
        ];
    }

    private function withdraw()
    {
        $total = Transaction::withdraw()
            ->sumAmount()
            ->completed()
            ->first()
            ->toArray()['amount'];

        $thisWeek = Transaction::withdraw()
            ->sumAmount()
            ->completed()
            ->thisWeek()
            ->first()
            ->toArray()['amount'];

        $lastWeek = Transaction::withdraw()
            ->completed()
            ->sumAmount()
            ->lastWeek()
            ->first()
            ->toArray()['amount'];

        $thisMonth = Transaction::withdraw()
            ->sumAmount()
            ->completed()
            ->thisMonth()
            ->first()
            ->toArray()['amount'];

        $lastMonth = Transaction::withdraw()
            ->completed()
            ->sumAmount()
            ->lastMonth()
            ->first()
            ->toArray()['amount'];

        return [
            'total' => $total,
            'this_week' => $thisWeek,
            'this_month' => $thisMonth,
            'last_month' => $lastMonth,
            'prtc_weekly' => to_dfp($thisWeek, $lastWeek),
            'prtc_monthly' => to_dfp($thisMonth, $lastMonth)
        ];
    }

    private function transactions()
    {
        $transactions   = Transaction::completed()->orderBy('completed_at', 'DESC')->where('type', '!=', TransactionType::INVESTMENT)->take(10)->get();

        $lastMonth      = [Carbon::now()->subMonth()->startOfMonth()->tz(time_zone()), Carbon::now()->tz(time_zone())];

        $deposit_since  = Transaction::completed()->whereBetween('completed_at', $lastMonth)->deposit()->count();
        $withdraw_since = Transaction::completed()->whereBetween('completed_at', $lastMonth)->withdraw()->count();
        $tnx_since      = Transaction::completed()->whereBetween('completed_at', $lastMonth)->count();

        $tnx_count      = Transaction::completed()->count();

        $credits        = Transaction::credits()->completed()->orderBy('completed_at', 'DESC')->where('type', '!=', TransactionType::INVESTMENT)->take(10)->get();
        $debits         = Transaction::debits()->completed()->orderBy('completed_at', 'DESC')->where('type', '!=', TransactionType::INVESTMENT)->take(10)->get();

        $dp_count       = Transaction::deposit()->completed()->count();
        $wd_count       = Transaction::withdraw()->completed()->count();

        $investments    = IvInvest::orderBy('id', 'DESC')->take(10)->get();

        return [
            'all_tnx'   => $transactions,
            'debits'    => $debits,
            'credits'   => $credits,
            'tnx_count' => $tnx_count,
            'dp_count'  => $dp_count,
            'wd_count'  => $wd_count,
            'dp_since'  => $deposit_since,
            'wd_since'  => $withdraw_since,
            'tnx_since' => $tnx_since,
            'investments' => $investments
        ];
    }

    private function userActivities(GraphData $graph)
    {
        $date = [Carbon::now()->subMonth()->startOfMonth()->tz(time_zone()), Carbon::now()->endOfMonth()->tz(time_zone())];

        $this_month = User::where('status', UserStatus::ACTIVE)->where('role', UserRoles::USER)->whereBetween('created_at', $date)->count();
        $ref_count  = User::where('refer', '!=', null)->whereBetween('created_at', $date)->count();

        $userQuery = User::selectRaw('count(id) as total, created_at as date')
            ->where('status', UserStatus::ACTIVE)->where('role', UserRoles::USER)->whereBetween('created_at', $date);

        $directGraph    = $userQuery->groupBy(DB::RAW('CAST(created_at as DATE)'))->get();
        $referralChart  = $userQuery->where('refer', '!=', null)->get();

        $graph->set('total', 'date', 'd M', 'quantity');

        $all = User::where('status', UserStatus::ACTIVE)->where('role', UserRoles::USER)->count();

        return [
            'user_count'    => $all,
            'ref_count'     => $ref_count,
            'this_month'    => $this_month,
            'referralGraph' => $graph->getDays($referralChart, 30)->get(),
            'directGraph'   => $graph->getDays($directGraph, 30)->get()
        ];
    }
}
