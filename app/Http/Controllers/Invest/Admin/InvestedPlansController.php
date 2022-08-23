<?php

namespace App\Http\Controllers\Invest\Admin;

use App\Models\User;
use App\Models\IvInvest;
use App\Models\Account;

use App\Enums\UserRoles;
use App\Enums\UserStatus;
use App\Enums\InvestmentStatus;
use App\Enums\AccountBalanceType;

use App\Jobs\ProcessEmail;
use App\Filters\PlansFilter;
use App\Services\InvestormService;
use App\Services\Investment\IvBalanceTransferService;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;

class InvestedPlansController extends Controller
{
    private $investment;

    public function __construct(InvestormService $investment)
    {
        $this->investment = $investment;
    }

    public function investedPlanList(Request $request, $status = null)
    {
        if (is_null($status) || !in_array($status, array_keys(get_enums(InvestmentStatus::class)))) {
            $whereIn = [InvestmentStatus::PENDING, InvestmentStatus::ACTIVE, InvestmentStatus::INACTIVE, InvestmentStatus::COMPLETED, InvestmentStatus::CANCELLED];
            $listing = 'all';
        } else {
            $whereIn = [$status];
            $listing = $status;
        }

        $filter = new PlansFilter($request);
        $pendingCount = IvInvest::where('status', InvestmentStatus::PENDING)->count();
        $investmentQuery = IvInvest::whereIn('status', $whereIn)->orderBy('id', user_meta('iv_invest_order', 'desc'))->filter($filter);
        $investments = $investmentQuery->paginate(user_meta('iv_invest_perpage', '10'));

        return view('investment.admin.invest.list', compact('investments', 'listing', 'pendingCount'));
    }

    public function processPlans()
    {
        if (is_locked('plan')) {
            throw ValidationException::withMessages(['invalid' => __("Sorry, one of your system administrator is working, please try again after few minutes.")]);
        }

        $plans = IvInvest::where('status', InvestmentStatus::ACTIVE)->get('id')->toArray();

        if (empty($plans)) {
            throw ValidationException::withMessages(['invalid' => ['title' => __('No actived plan!'), 'msg' => __('There is no active invested plan for syncing.')]]);
        }

        $getIDs = array_column($plans, 'id');
        $planSets = array_chunk($getIDs, 5);

        return view('investment.admin.misc.modal-process-plans', [ 'plans' => $planSets, 'total' => count($plans) ]);
    }

    public function showInvestmentDetails($id)
    {
        $invest = IvInvest::find(get_hash($id));

        if(blank($invest)) {
            return redirect()->route('admin.investment.list');
        }
        try {
            if (in_array($invest->status, [InvestmentStatus::ACTIVE])) {
                $this->wrapInTransaction(function($invest){
                    $this->investment->processInvestmentProfit($invest);
                }, $invest);
            }
        } catch (\Exception $e) {
            save_error_log($e, 'invest-details');
        }

        $invest = $invest->fresh()->load(['profits' => function($q) {
            $q->orderBy('id', 'asc');
        }]);

        return view("investment.admin.invest.details", compact("invest"));
    }

    public function processSyncPlans(Request $request)
    {
        if (empty($request->get('done')) && is_locked('plan')) {
            throw ValidationException::withMessages(['invalid' => __("Sorry, one of your system administrator is working, please try again after few minutes.")]);
        }

        $request->validate([
            'batchs' => 'required|array',
            'action' => 'nullable',
            'done' => 'nullable',
            'total' => 'nullable',
            'idx' => 'nullable',
        ], [
            'batchs.*' => __("Sorry, unable to proceed for invalid data format.") . ' ' . __("Please reload the page and try again.")
        ]);


        $plans = $request->get('batchs');
        $done = (int) $request->get('done', 0);
        $total = (int) $request->get('total', 0);
        $idx = (int) $request->get('idx', 0);

        if ($done == 0) {
            $time = now()->timestamp;
            upss('payout_locked_plan', $time);
        }

        foreach ($plans as $id) {
            $invest = IvInvest::find($id);
            try {
                if (in_array($invest->status, [InvestmentStatus::ACTIVE])) {
                    $this->wrapInTransaction(function($invest){
                        $this->investment->processInvestmentProfit($invest);
                    }, $invest);
                }
            } catch (\Exception $e) {
                save_msg_log($e->getMessage(), 'notice');
            }
            $done++;
        }

        $left = ($total - $done);
        $progress = (($done / $total) * 100);
        $next = ($left == 0 || $total <= $done) ? false : true;

        if ($left == 0) {
            upss('payout_locked_plan', null);
            $message = __("All investment plans successfully synced.");
        } else {
            $message = __("Investment batch processed.");
        }

        return response()->json([
            'status' => 'success', 'message' => $message, 'idx' => ($idx + 1),
            'done' => $done, 'total' => $total, 'progress' => $progress, 'next' => $next
        ]);
    }

    public function cancelInvestment(Request $request, $id=null)
    {
        $ivID = ($request->get("uid")) ? get_hash($request->get("uid")) : get_hash($id);

        $reload = (empty($request->get('reload')) || $request->get('reload')=='false') ? false : true;

        $invest = IvInvest::find($ivID);

        if (blank($invest) || in_array($invest->status, [InvestmentStatus::CANCELLED, InvestmentStatus::COMPLETED])) {
            throw ValidationException::withMessages(['invalid' => __('Sorry, unable to cancel the investment plan.') ]);
        }

        return $this->wrapInTransaction(function ($invest, $reload) {
            $this->investment->cancelSubscription($invest);
            return response()->json([ 'title' => __("Plan Cancelled"), 'msg' => __('The investment plan has been cancelled.'), 'reload' => $reload ]);
        }, $invest, $reload);

        return response()->json([ 'type' => 'error', 'msg' => __('Sorry, unable to cancel the investment plan.') ]);
    }

    public function approveInvestment(Request $request, $id=null)
    {
        $ivID = ($request->get("uid")) ? get_hash($request->get("uid")) : get_hash($id);

        $ivInvestment = IvInvest::find($ivID);

        if (filled($ivInvestment)) {
            try {
                $this->wrapInTransaction(function ($ivInvestment, $request){
                    $investment = $this->investment->approveSubscription($ivInvestment, strip_tags($request->get('remarks')), strip_tags($request->get('note')));
                    
                    try {
                        ProcessEmail::dispatch('investment-approved-customer', data_get($ivInvestment, 'user'), null, $ivInvestment);
                        ProcessEmail::dispatch('investment-approved-admin', data_get($ivInvestment, 'user'), null, $ivInvestment);
                    } catch (\Exception $e) {
                        save_mailer_log($e, 'investment-placed');
                    }
                }, $ivInvestment, $request);

                return response()->json(['title' => __("Plan Approved"), 'msg' => __('The investment plan has been approved and stated for profit distribution.'), 'reload' => true ]);
            } catch (\Exception $e) {
                save_error_log($e, 'invest-approve');
            }
        }

        return response()->json([ 'type' => 'error', 'msg' => __('Sorry, unable to approve the investment plan.') ]);
    }

    public function completeInvestment(Request $request, $id = null)
    {
        $ivID = ($request->get("uid")) ? get_hash($request->get("uid")) : get_hash($id);

        $ivInvestment = IvInvest::find($ivID);

        if (blank($ivInvestment) || in_array($ivInvestment->status, [InvestmentStatus::CANCELLED, InvestmentStatus::COMPLETED])) {
            throw ValidationException::withMessages(['invalid' => __('Sorry, unable to complete the investment plan.')]);
        }

        if ($ivInvestment->term_count !== $ivInvestment->term_total) {
            throw ValidationException::withMessages(['invalid' => __('Sorry, unable to complete the investment plan.')]);
        }

        $this->wrapInTransaction(function ($investment) {
            $this->investment->proceedPayout($investment->user_id, $investment->profits->pluck('id')->toArray());
            $this->investment->processCompleteInvestment($investment);
        }, $ivInvestment);

        return response()->json([
            'type' => 'success',
            'msg' => __("Investment successfully completed."),
            'reload' => true
        ]);
    }

    public function processTransfers()
    {
        if (gss('iv_auto_transfer', 'no') == 'no') {
            throw ValidationException::withMessages(['invalid' => ['title' => __('Auto Transfer Unavailable!'), 'msg' => __('Auto balance transfer system is currently disabled.')]]);
        }

        if (is_locked('transfer')) {
            throw ValidationException::withMessages(['invalid' => __("Sorry, one of your system administrator is working, please try again after few minutes.")]);
        }

        $defaultMin = (double) gss('iv_min_transfer', 0.001);
        $accounts = Account::where('balance', AccountBalanceType::INVEST)->where('amount', '>=', $defaultMin)->get()->toArray();

        if (empty($accounts)) {
            throw ValidationException::withMessages(['invalid' => ['title' => __('No Balance Transfer!'), 'msg' => __('There is no account to make transfer right now.')]]);
        }

        $getIDs = array_column($accounts, 'user_id');
        $users = array_chunk($getIDs, 5);

        return view('investment.admin.misc.modal-process-transfers', ['users' => $users, 'total' => count($accounts)]);
    }

    public function completeTransfers(Request $request)
    {
        if (empty($request->get('done')) && is_locked('transfer')) {
            throw ValidationException::withMessages(['invalid' => __("Sorry, one of your system administrator is working, please try again after few minutes.")]);
        }

        $request->validate([
            'batchs' => 'required|array',
            'action' => 'nullable',
            'done' => 'nullable',
            'total' => 'nullable',
            'idx' => 'nullable',
        ], [
            'batchs.*' => __("Sorry, unable to proceed for invalid data format.") . ' ' . __("Please reload the page and try again.")
        ]);

        $users = $request->get('batchs');
        $done = (int) $request->get('done', 0);
        $total = (int) $request->get('total', 0);
        $idx = (int) $request->get('idx', 0);

        if ($done == 0) {
            $time = now()->timestamp;
            upss('payout_locked_transfer', $time);
        }

        foreach ($users as $id) {
            $user = User::find($id);
            try {
                if (!blank($user) && in_array($user->status, [UserStatus::ACTIVE]) && in_array($user->role, [UserRoles::USER])) {
                    $this->wrapInTransaction(function($user) {
                        $transferProcessor = new IvBalanceTransferService();
                        $transferProcessor->setUser($user)->autoTransfer();
                    }, $user);
                }
            } catch (\Exception $e) {
                save_msg_log($e->getMessage(), 'notice');
            }
            $done++;
        }

        $left = ($total - $done);
        $progress = (($done / $total) * 100);
        $next = ($left == 0 || $total <= $done) ? false : true;

        if ($left == 0) {
            upss('payout_locked_transfer', null);
            $message = __("All available balance successfully transferred.");
        } else {
            $message = __("Available balance transfer batch processed.");
        }

        return response()->json([
            'status' => 'success', 'message' => $message, 'idx' => ($idx + 1),
            'done' => $done, 'total' => $total, 'progress' => $progress, 'next' => $next
        ]);
    }
}
