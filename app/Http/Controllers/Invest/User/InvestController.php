<?php

namespace App\Http\Controllers\Invest\User;

use App\Enums\SchemeStatus;
use App\Enums\InvestmentStatus;
use App\Models\IvInvest;
use App\Models\IvScheme;

use App\Jobs\ProcessEmail;
use App\Helpers\MsgState;
use App\Services\InvestormService;

use Brick\Math\BigDecimal;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;

class InvestController extends Controller
{
    private $investment;

    public function __construct(InvestormService $investment)
    {
        $this->investment = $investment;
    }


    public function showPlans(Request $request, $ucode = null)
    {
        if (sys_settings('iv_disable_purchase') == 'yes') {
            $errors = MsgState::of('disable', 'invest');
            return view("investment.user.invest.errors", $errors);
        }

        if (kyc_required('invest', true)) {
            $errors = module_msg_of('invest', 'error', 'BasicKYC');
            return view("investment.user.invest.errors", $errors);
        }

        $balance = user_balance(AccType('main'));

        // Quick Selection
        $singleScheme = (!empty($ucode)) ? $this->getPlan($ucode) : false;
        $single = (!empty($singleScheme)) ? true : false;

        // All Scheme Listing
        $schemes = (!empty($singleScheme)) ? $singleScheme->plan : $this->getSchemes();
        $plans = [];

        if (empty($schemes)) {
            $errors = MsgState::of('no-plan', 'invest');
            return view("investment.user.invest.errors", $errors);
        }
        if (empty($balance)) {
            $errors = MsgState::of('no-balance', 'invest');
            return view("investment.user.invest.errors", $errors);
        }

        if ($single == true) {
            $plans = $singleScheme->data;

            if (BigDecimal::of($schemes->amount)->compareTo($balance) > 0) {
                $errors = MsgState::of('no-funds', 'invest');
                return view("investment.user.invest.errors", $errors);
            }
        } else {
            foreach ($schemes as $plan) {
                $plans[$plan->uid_code] = [
                    'amount' => $plan->amount,
                    'fixed' => $plan->is_fixed,
                    'min' => ($plan->amount) ? money($plan->amount, base_currency()) : 0,
                    'max' => ($plan->maximum) ? money($plan->maximum, base_currency()) : 0
                ];
            }
        }

        return view("investment.user.invest", compact('schemes', 'plans', 'single'));
    }


    public function previewInvest(Request $request)
    {
        $currency = base_currency();

        $input = $request->validate([
            'scheme' => 'required',
            'amount' => 'required|numeric|not_in:0',
            'source' => 'required'
        ], [
            'scheme.required' => __("Please choose an investment plan."),
            'amount.*' => __("Please enter an amount for investment."),
            'source.required' => __("Please select your payment source.")
        ]);

        // Investment Plan
        $plan = IvScheme::find(get_hash($input['scheme']));
        if (blank($plan) || (!blank($plan) && $plan->status != SchemeStatus::ACTIVE)) {
            throw ValidationException::withMessages(['plan' => __('The selected plan may not available or invalid.')]);
        }

        // Plan Purchase Limit
        $getPurchaseLimit = (int) $plan->meta('plan_limit');
        $countPlans = $plan->plans->whereIn('status', ['pending', 'active', 'completed'])->count();
        if (!empty($getPurchaseLimit) && $countPlans >= $getPurchaseLimit) {
            throw ValidationException::withMessages(['error' => __('Sorry, the selected plan has been reached maximum times of investment.')]);
        }

        // User Plan Limit
        $getUserLimit = (int) $plan->meta('plan_limit_user');
        $countUserPlans = IvInvest::where('user_id', auth()->id())->where('scheme_id', $plan->id)->whereIn('status', ['pending', 'active', 'completed'])->count();
        $countUserCancelled = IvInvest::where('user_id', auth()->id())->where('scheme_id', $plan->id)->where('status', 'cancelled')->with('actions')->has('actions', '>=', 1, 'and', function ($query) {
            $query->where('action', 'refund');
        })->count();
        $countUserInvestedPlan = $countUserPlans + $countUserCancelled;
        if (!empty($getUserLimit) && $countUserInvestedPlan >= $getUserLimit) {
            throw ValidationException::withMessages(['error' => __('Sorry, you cannot invest on this selected plan as per our investment limitation.')]);
        }

        // Payment Source
        $account = 'unknown';
        $source = ($input['source']) ? $input['source'] : 'wallet';
        if (in_array($source, ['wallet', 'account'])) {
            $account = ($source == 'account') ? AccType('invest') : AccType('main');
        } else {
            throw ValidationException::withMessages(['source' => __('Sorry, your payment account is not valid.')]);
        }

        // Amount & Balance
        $amount = ($input['amount']) ? (float)$input['amount'] : 0;
        $balance = user_balance($account);

        if (empty($amount)) {
            throw ValidationException::withMessages([
                'amount' => __('Sorry, the investment amount is not valid.')
            ]);
        } elseif (empty($balance)) {
            throw ValidationException::withMessages([
                'account' => __('Sorry, not enough balance in selected account.')
            ]);
        }

        if ($plan->is_fixed && BigDecimal::of($amount)->compareTo($plan->amount) !== 0) {
            throw ValidationException::withMessages([
                'amount' => __('The investment amount should be :amount', ['amount' => money($plan->amount, $currency)])
            ]);
        } else {
            if (BigDecimal::of($amount)->compareTo($plan->amount) == -1) {
                throw ValidationException::withMessages([
                    'amount' => __('The minimum amount of :amount is required to invest on selected plan.', ['amount' => money($plan->amount, $currency)])
                ]);
            }

            if (!empty($plan->maximum) && BigDecimal::of($plan->maximum)->compareTo($plan->amount) >= 0 && BigDecimal::of($amount)->compareTo($plan->maximum) == 1) {
                throw ValidationException::withMessages([
                    'amount' => __('You can invest maximum :amount on selected investment plan.', ['amount' => money($plan->maximum, $currency)])
                ]);
            }
        }

        if (BigDecimal::of($amount)->compareTo($balance) > 0) {
            $errors = MsgState::of('no-funds', 'invest');
            return view("investment.user.invest.failed", $errors);
        }

        $input['amount'] = $amount;
        $input['source'] = $account;
        $input['currency'] = $currency;

        $details = $this->investment->processSubscriptionDetails($input, $plan, $amount);

        if (empty($details)) {
            throw ValidationException::withMessages(["scheme" => __("Sorry unable process subscription")]);
        }

        $request->session()->put('invest_details', $details);
        return view("investment.user.invest.preview", compact("details", "plan", "currency"));
    }


    public function confirmInvest(Request $request)
    {
        $subscription = $request->session()->get('invest_details');
        $plan = IvScheme::find(data_get($subscription, 'scheme_id'));
        $revalidate = false;

        if (empty($subscription)) {
            $revalidate = true;
        }

        // Investment Plan
        if (blank($plan) || (!blank($plan) && $plan->status != SchemeStatus::ACTIVE)) {
            $revalidate = true;
        }

        // Plan Purchase Limit
        $getPurchaseLimit = (int) $plan->meta('plan_limit');
        $countPlans = $plan->plans->whereIn('status', ['pending', 'active', 'completed'])->count();
        if (!empty($getPurchaseLimit) && $countPlans >= $getPurchaseLimit) {
            $revalidate = true;
        }

        // User Plan Limit
        $getUserLimit = (int) $plan->meta('plan_limit_user');
        $countUserPlans = IvInvest::where('user_id', auth()->id())->where('scheme_id', $plan->id)->whereIn('status', ['pending', 'active', 'completed'])->count();
        $countUserCancelled = IvInvest::where('user_id', auth()->id())->where('scheme_id', $plan->id)->where('status', 'cancelled')->with('actions')->has('actions', '>=', 1, 'and', function ($query) {
            $query->where('action', 'refund');
        })->count();
        $countUserInvestedPlan = $countUserPlans + $countUserCancelled;
        if (!empty($getUserLimit) && $countUserInvestedPlan >= $getUserLimit) {
            $revalidate = true;
        }

        if ($revalidate == true) {
            $errors = MsgState::of('wrong', 'invest');
            return view("investment.user.invest.failed", $errors);
        }

        return $this->wrapInTransaction(function ($subscription) {
            $invest = $this->investment->confirmSubscription($subscription);
            if (iv_start_automatic()) {
                $this->investment->approveSubscription($invest, 'auto-approved');
                $invest->fresh();
            }

            try {
                ProcessEmail::dispatch('investment-placed-customer', data_get($invest, 'user'), null, $invest);
                ProcessEmail::dispatch('investment-placed-admin', data_get($invest, 'user'), null, $invest);
            } catch (\Exception $e) {
                save_mailer_log($e, 'investment-placed');
            }

            return view("investment.user.invest.success", compact('invest'));
        }, $subscription);
    }


    public function cancelInvestment($id, Request $request)
    {
        $invest = IvInvest::loggedUser()->where('id', get_hash($id))
            ->where('status', InvestmentStatus::PENDING)
            ->first();

        if (blank($invest) || (data_get($invest, 'user_can_cancel') == false)) {
            throw ValidationException::withMessages(['id' => __('Sorry unable to cancel investment!')]);
        }

        return $this->wrapInTransaction(function ($invest) {
            $this->investment->cancelSubscription($invest);

            try {
                ProcessEmail::dispatch('investment-cancel-user-customer', data_get($invest, 'user'), null, $invest);
                ProcessEmail::dispatch('investment-cancel-user-admin', data_get($invest, 'user'), null, $invest);
            } catch (\Exception $e) {
                save_mailer_log($e, 'investment-cancel-user-customer');
            }

            return response()->json(['msg' => __('Investment cancelled successfully!')]);
        }, $invest);
    }

    private function getID($uid)
    {
        $theID = str_replace('IV', '', substr($uid, 0, -3));
        return (int)$theID;
    }

    private function getPlan($uid)
    {
        $id = $this->getID($uid);
        $plan = IvScheme::where('id', $id)->where('status', SchemeStatus::ACTIVE)->first();

        if (!blank($plan)) {
            $data[$plan->uid_code] = [
                'amount' => $plan->amount,
                'fixed' => $plan->is_fixed,
                'min' => ($plan->amount) ? money($plan->amount, base_currency()) : 0,
                'max' => ($plan->maximum) ? money($plan->maximum, base_currency()) : 0
            ];

            return (object)['plan' => $plan, 'data' => $data];
        }

        return false;
    }

    private function getSchemes()
    {
        $schemeQuery = IvScheme::query();
        $schemeOrder = sys_settings('iv_plan_order');

        switch ($schemeOrder) {
            case "reverse":
                $schemeQuery->orderBy('id', 'desc');
                break;
            case "random":
                $schemeQuery->inRandomOrder();
                break;
            case "featured":
                $schemeQuery->orderBy('featured', 'desc');
                break;
        }

        $schemes = $schemeQuery->where('status', SchemeStatus::ACTIVE)->get();

        return (!blank($schemes)) ? $schemes : false;
    }
}
