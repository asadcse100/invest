<?php

namespace App\Http\Controllers\Invest\User;

use App\Enums\SchemeStatus;
use App\Enums\LedgerTnxType;
use App\Enums\InvestmentStatus;

use App\Models\UserMeta;
use App\Models\IvLedger;
use App\Models\IvProfit;
use App\Models\IvInvest;
use App\Models\IvScheme;

use App\Helpers\MsgState;
use App\Filters\PlansFilter;
use App\Filters\LedgerFilter;

use App\Services\GraphData;
use App\Services\InvestormService;
use App\Services\Investment\IvBalanceTransferService;

use Brick\Math\BigDecimal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;

class InvestmentController extends Controller
{
    private $investment;

    public function __construct(InvestormService $investment)
    {
        $this->investment = $investment;
    }

    public function index(Request $request, GraphData $graph)
    {
        $amounts = [];
        $user_id = auth()->id();

        $investments = IvInvest::whereIn('status', [
            InvestmentStatus::PENDING,
            InvestmentStatus::ACTIVE,
            InvestmentStatus::COMPLETED
        ])->where('user_id', $user_id)
            ->orderBy('id', 'desc')->get()->groupBy('status');

        $recents = IvInvest::where('user_id', $user_id)->where('status', InvestmentStatus::COMPLETED)->latest()->limit(3)->get();
        $actived = IvInvest::where('user_id', $user_id)->where('status', InvestmentStatus::ACTIVE)->get();

        // Profit calculate
        if($actived->count() > 0) {
            $this->bulkCalculate($actived);
        }

        $invested = IvInvest::where('user_id', $user_id)->where('status', InvestmentStatus::ACTIVE);
        $pending = IvInvest::where('user_id', $user_id)->where('status', InvestmentStatus::PENDING);
        $profits = IvProfit::where('user_id', $user_id)->whereNull('payout');

        $amounts['invested'] = $invested->sum('amount');
        $amounts['profit'] = $invested->sum('profit');
        $amounts['locked'] = to_sum($profits->sum('amount'), $pending->sum('amount'));

        // Graph chart        
        $graph->set('profit', 'term_start');

        $profit = IvInvest::select(DB::raw('SUM(profit) as profit,term_start'))
                ->where('status', InvestmentStatus::ACTIVE)
                ->groupBy(DB::RAW('CAST(term_start as DATE)'))
                ->get();

        $profitChart = $graph->getDays($profit, 31)->flatten();

        $graph->set('amount', 'term_start');

        $investment = IvInvest::select(DB::raw('SUM(amount) as amount,term_start'))
                    ->where('status', InvestmentStatus::ACTIVE)
                    ->groupBy(DB::RAW('CAST(term_start as DATE)'))
                    ->get();

        $investChart = $graph->getDays($investment, 31)->flatten();


        return view("investment.user.dashboard", compact('investments', 'recents', 'amounts', 'profitChart', 'investChart'));
    }


    public function planList(Request $request)
    {
        $planQuery = IvScheme::query();
        $planOrder = sys_settings('iv_plan_order');

        switch ($planOrder) {
            case "reverse":
                $planQuery->orderBy('id', 'desc');
                break;
            case "random":
                $planQuery->inRandomOrder();
                break;
            case "featured":
                $planQuery->orderBy('featured', 'desc');
                break;
        }

        $plans = $planQuery->where('status', SchemeStatus::ACTIVE)->get();

        if(blank($plans)) {
            $errors = MsgState::of('no-plan', 'invest');
            return view("investment.user.invest.errors", $errors);
        }

        return view("investment.user.invest-plans", compact('plans'));
    }

    public function investmentHistory(Request $request, $status = null)
    {
        $input = array_filter($request->only(['status', 'query']));
        $eligibleStatus = [
            InvestmentStatus::PENDING,
            InvestmentStatus::ACTIVE,
            InvestmentStatus::COMPLETED
        ];

        $investCount = IvInvest::select('id', 'status')->loggedUser()->get()
            ->groupBy('status')->map(function($item){
                return count($item);
            });

        $filter = new PlansFilter(new Request(array_merge($input, ['status' => $status ?? $request->get('status')])));
        $investQuery = IvInvest::loggedUser()->orderBy('id', 'desc')->filter($filter);

        if ($status && in_array($status, $eligibleStatus)) {
            $investQuery->where('status', $status);
            $listing = $status;
        } else {
            $listing = 'all';
        }

        $investments = $investQuery->paginate(user_meta('iv_history_perpage', 20))->onEachSide(0);

        return view("investment.user.history", compact('investments', 'investCount', 'listing'));
    }

    public function investmentDetails($id)
    {
        $invest = IvInvest::find(get_hash($id));

        if (blank($invest)) {
            throw ValidationException::withMessages(['invest' => 'Invalid Investment!']);
        }

        $this->profitCalculate($invest);

        $invest = $invest->fresh()->load(['profits' => function ($q) {
            $q->orderBy('term_no', 'asc');
        }]);

        return view("investment.user.plan", compact("invest"));
    }

    public function transactionList(Request $request, $type=null)
    {
        $input = array_filter($request->only(['type', 'source', 'query']));
        $filterCount = count(array_filter($input, function ($item) {
            return !empty($item) && $item !== 'any';
        }));
        $filter = new LedgerFilter(new Request(array_merge($input, ['type' => $type ?? $request->get('type')])));
        $ledgers = get_enums(LedgerTnxType::class, false);
        $sources = [AccType('main'), AccType('invest')];

        $transactions = IvLedger::loggedUser()->with(['invest'])->orderBy('id', 'desc')
            ->filter($filter)
            ->paginate(user_meta('iv_tnx_perpage', 10))->onEachSide(0);

        return view("investment.user.transactions", [
            'transactions' => $transactions,
            'sources' => $sources,
            'ledgers' => $ledgers,
            'type'    => $type ?? 'all',
            'filter_count' => $filterCount,
            'input' => $input,
        ]);
    }

    public function payoutInvest(Request $request)
    {
        $min = min_to_compare();
        $balance = user_balance(AccType('invest'));

        if ($request->ajax()) {
            $balance = (BigDecimal::of($balance)->compareTo($min) != 1) ? false : $balance;

            return view("investment.user.misc.modal-payout", compact("balance"))->render();
        } else {
            return redirect()->route('user.investment.dashboard');
        }
    }

    public function payoutProceed(Request $request)
    {
        $this->validate($request, [
            'amount' => ['required', 'numeric', 'gt:0']
        ], [
            'amount.required' => __('Enter a valid amount to transfer funds.'),
            'amount.numeric' => __('Enter a valid amount to transfer funds.'),
        ]);

        $min = min_to_compare();
        $amount = $request->get('amount');
        $balance = user_balance(AccType('invest'));

        if (BigDecimal::of($balance)->compareTo($min) != 1) {
            throw ValidationException::withMessages([ 'invalid' => __("You do not have enough funds in your account to transfer.") ]);
        }
        if (BigDecimal::of($amount)->compareTo($balance) > 0) {
            throw ValidationException::withMessages(['amount' => ['title' => __('Insufficient balance!'), 'message' => __('The amount exceeds your available funds.')]]);
        }

        if (BigDecimal::of($amount)->compareTo($min) != 1) {
            throw ValidationException::withMessages([ 'invalid' => __("The amount is required to transfer.") ]);
        }

        $transfer = $this->wrapInTransaction(function($amount) {
            $transferProcessor = new IvBalanceTransferService();
            return $transferProcessor->setUser(auth()->user())->manualTransfer($amount);
        }, $amount);

        if ($transfer) {
            return response()->json(['title' => __('Fund Transfered'), 'msg' => __('Your funds successfully transferred into your main account balance.'), 'reload' => true ]);
        } else {
            throw ValidationException::withMessages(['warning' => __('Sorry, something went wrong! Please reload the page and try again.')]);
        }
    }

    private function profitCalculate(IvInvest $invest)
    {
        if(empty($invest)) return false;

        try {
            if (in_array($invest->status, [InvestmentStatus::ACTIVE, InvestmentStatus::COMPLETED])) {
                $this->wrapInTransaction(function ($invest) {
                    $this->investment->processInvestmentProfit($invest);
                }, $invest);
            }
        } catch (\Exception $e) {
            save_error_log($e, 'profit-calc');
        }

        return true;
    }

    private function bulkCalculate($investments)
    {
        if(empty($investments)) return false;

        foreach ($investments as $invest) {
            $this->profitCalculate($invest);
        }

        return true;
    }

    public function ivSettings(Request $request)
    {
        $user = auth()->user();
        $metas = UserMeta::where('user_id', $user->id)->pluck('meta_value', 'meta_key')->toArray();
        $setting = $user->auto_transfer_settings;

        if ($request->ajax()) {
            return view('investment.user.misc.modal-settings', compact('metas', 'setting'))->render();
        } else {
            return redirect()->route('user.investment.dashboard');
        }
    }

    public function saveIvSettings(Request $request)
    {
        $input = $request->validate([
            'auto_transfer' => 'string',
            'min_transfer' => 'bail|nullable|numeric|gte:'.gss("iv_min_transfer")
        ], [
            'min_transfer.numeric' => __('Minimum amount must be a valid number.'), 
            'min_transfer.gte' => __('Minimum amount must be greater than or equal to :amount.', ['amount' => money(gss("iv_min_transfer"), base_currency())]), 
        ]);
        $input['auto_transfer'] = $request->get('auto_transfer', 'off');

        foreach ($input as $key => $value) {
            UserMeta::updateOrCreate([
                'user_id' => auth()->user()->id,
                'meta_key' => 'setting_'.$key
            ], ['meta_value' => $value ?? null]);
        }
        
        return response()->json(['title' => __('Settings Updated!'), 'msg' => __('Auto Transfer setting has been updated successfully.'), 'reload' => true]);
    }
}
