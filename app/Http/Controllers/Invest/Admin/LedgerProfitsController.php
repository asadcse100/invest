<?php

namespace App\Http\Controllers\Invest\Admin;

use App\Enums\AccountBalanceType;
use App\Enums\LedgerTnxType;
use App\Enums\InvestmentStatus;
use App\Enums\SchemePayout;
use App\Enums\TransactionCalcType;
use App\Enums\UserRoles;
use App\Enums\UserStatus;
use App\Models\IvInvest;
use App\Models\IvLedger;
use App\Models\IvProfit;

use App\Filters\LedgerFilter;
use App\Filters\ProfitFilter;
use App\Services\InvestormService;
use App\Traits\WrapInTransaction;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Investment\IvTransactionService;
use Brick\Math\BigDecimal;
use Illuminate\Validation\ValidationException;

class LedgerProfitsController extends Controller
{
    use WrapInTransaction;

    private $investment;

    public function __construct(InvestormService $investment)
    {
        $this->investment = $investment;
    }

    public function transactionList(Request $request, $type = null)
    {
        $input = array_filter($request->only(['type', 'source', 'query', 'user']));
        $filterCount = count(array_filter($input, function ($item) {
            return !empty($item) && $item !== 'any';
        }));
        $filter = new LedgerFilter(new Request(array_merge($input, ['type' => $type ?? $request->get('type')])));
        $ledgers = get_enums(LedgerTnxType::class, false);
        $sources = [AccType('main'), AccType('invest')];

        $transactionQuery = IvLedger::orderBy('id', user_meta('iv_tnx_order', 'desc'))
            ->filter($filter);

        $transactions = $transactionQuery->paginate(user_meta('iv_tnx_perpage', 10))->onEachSide(0);


        return view('investment.admin.statement.transactions', [
            'transactions' => $transactions,
            'sources' => $sources,
            'ledgers' => $ledgers,
            'type' => $type ?? 'all',
            'filter_count' => $filterCount,
            'input' => $input,
        ]);
    }

    public function profitList(Request $request, ProfitFilter $filter, $type = null)
    {
        $profitQuery = IvProfit::orderBy('iv_profits.id', user_meta('iv_profit_order', 'desc'))->with(['invest', 'invest_by'])
            ->filter($filter);

        if ($type == 'pending') {
            $profitQuery->whereNull('payout');
        }

        $profits = $profitQuery->paginate(user_meta('iv_profit_perpage', 20))->onEachSide(0);

        return view('investment.admin.statement.profits', [
            'profits' => $profits,
            'type' => $type ?? 'all'
        ]);
    }

    public function processProfits(Request $request)
    {
        if (is_locked('profit')) {
            throw ValidationException::withMessages(['invalid' => __("Sorry, one of your system administrator is working, please try again after few minutes.")]);
        }

        $ivID = get_hash($request->get('id'));

        $invests = IvInvest::find($ivID);

        if (!blank($invests)) {
            if ($invests->payout_type === SchemePayout::AFTER_MATURED && $invests->remaining_term !== 0) {
                throw ValidationException::withMessages(['error' => __('Sorry, profits of this plan can be processed after the investment is matured.')]);
            }        
        } else {
            $invests = IvInvest::isActive()->get()
                ->filter(function ($invest) {
                    $payout = data_get($invest, 'scheme.payout');
                    if ($payout === SchemePayout::TERM_BASIS || ($payout === SchemePayout::AFTER_MATURED && $invest->remaining_term === 0)) {
                        return $invest;
                    }
                })->map(function ($invest) {
                    return $invest->id;
                })->toArray();
        }

        $payout = IvProfit::whereNull('payout')->whereIn('invest_id', is_array($invests) ? $invests : [$invests->id]);

        $amount = $payout->sum('amount');
        $total = $payout->count();

        $getProfits = $payout->orderBy('id', 'asc')->get()->groupBy('user_id');

        $userByProfits = $getProfits->map(function ($items, $key) {
            return $items->keyBy('id')->keys()->toArray();
        })->toArray();

        $accounts = [];
        foreach ($userByProfits as $user => $profits) {
            $accounts[] = [$user => $profits];
        }

        if (empty($accounts)) {
            if (isset($invests->id) && !empty($invests->id)) {
                if (data_get($invests, 'term_count') == data_get($invests, 'term_total')) {
                    throw ValidationException::withMessages(['invalid' => ['title' => __('All profits seems to paid!'), 'msg' => __('You can complete this investement plan.')]]);
                }
            }
            throw ValidationException::withMessages(['invalid' => ['title' => __('No pending profits!'), 'msg' => __('There is no pending profits to approve.')]]);
        }

        return view('investment.admin.misc.modal-process-profits', ['accounts' => $accounts, 'amount' => $amount, 'total' => $total, 'single' => $ivID ? the_hash($ivID) : null]);
    }

    public function processPayoutProfits(Request $request)
    {
        if (empty($request->get('done')) && is_locked('profit')) {
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

        $batchs = $request->get('batchs');
        $done = (int) $request->get('done', 0);
        $total = (int) $request->get('total', 0);
        $idx = (int) $request->get('idx', 0);
        $ivID = get_hash($request->get('iv'));

        if ($done == 0) {
            $time = now()->timestamp;
            upss('payout_locked_profit', $time);
        }

        if (!empty($batchs) && is_array($batchs)) {
            foreach ($batchs as $user_id => $profits) {
                $this->wrapInTransaction(function ($profits, $user_id) {
                    $this->investment->proceedPayout($user_id, $profits);
                }, $profits, $user_id);
                $done++;
            }
        }


        $left = ($total - $done);
        $progress = (($done / $total) * 100);
        $next = ($left == 0 || $total <= $done) ? false : true;

        if ($left == 0) {
            $invests = empty($ivID) ? IvInvest::where('status', InvestmentStatus::ACTIVE)->get() : IvInvest::where('id', $ivID)->get();
            if (!blank($invests)) {
                foreach ($invests as $invest) {
                    $this->wrapInTransaction(function ($invest) {
                        $this->investment->processCompleteInvestment($invest);
                    }, $invest);
                }
            }

            upss('payout_locked_profit', null);
            $message = __("All profits successfully approved and release the locked amount from user account.");
        } else {
            $message = __("Profits batch processed.");
        }

        return response()->json([
            'status' => 'success', 'message' => $message, 'idx' => ($idx + 1),
            'done' => $done, 'total' => $total, 'progress' => $progress, 'next' => $next
        ]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \ReflectionException
     * @version 1.0.0
     * @since 1.0
     */
    public function manualTnxAdd(Request $request)
    {

        $todo = $request->get("view") ?? 'any';

        if (!in_array($todo, [
            LedgerTnxType::PROFIT,
            LedgerTnxType::LOSS,
            LedgerTnxType::PENALTY,
            'any'
        ])) {
            throw ValidationException::withMessages(['invalid' => __("An error occurred. Unable to procced your request.")]);
        }

        $types = [
            'profit' => ['name' => LedgerTnxType::PROFIT, 'label' => __("Add Profit")],
            'loss' => ['name' => LedgerTnxType::LOSS, 'label' => __("Add Loss")],
            'penalty' => ['name' => LedgerTnxType::PENALTY, 'label' => __("Add Penalty")]
        ];

        $methods = [
            'manual' => ['name' => 'manual', 'label' => __("Manual / Direct")],
        ];

        $users = User::where('status', UserStatus::ACTIVE)->where('role', UserRoles::USER)->get();

        return response()->json(view('admin.transaction.modal.manual-form', [
            'users' => $users,
            'todo' => $todo,
            'types' => $types,
            'methods' => $methods,
            'ivTnx' => true
        ])->render());
    }

    public function manualTnxSave(Request $request)
    {   
        $request->validate([
            'amount' => ['required', 'numeric', 'gt:'.min_to_compare()],
            'account' => ['exists:App\Models\User,id'],
            'tnxtype' => ['required', 'string'],
            'tnxmethod' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'remarks' => ['nullable', 'string'],
        ], [
            'amount.numeric' => __('Amount should be valid number to add.'),
            'amount.required' => __('Please enter the amount to add transaction.'),
            'account.exists' => __('User account may not valid or selected.')
        ]);
        
        if (!in_array($request->tnxtype, [LedgerTnxType::PROFIT, LedgerTnxType::LOSS, LedgerTnxType::PENALTY])) {
            throw ValidationException::withMessages(['error' => __('Invalid action taken')]);
        }

        if (BigDecimal::of($request->amount)->compareTo(min_to_compare()) == 0) {
            throw ValidationException::withMessages(['warning' => __('The amount must be greater than :min', ['min' => min_to_compare()])]);
        }

        if (in_array($request->tnxtype, [LedgerTnxType::PENALTY, LedgerTnxType::LOSS])) {
            $userAccount = get_user_account($request->account, AccountBalanceType::INVEST);
            $balance = $userAccount->amount;
            if (BigDecimal::of($request->amount)->compareTo(BigDecimal::of($balance)->minus('0.01')) > 0) {
                throw ValidationException::withMessages(['invalid' => __("Unable to add transaction into investement account for insufficient balance.")]);
            }
        }

        $tnxData = [
            'user_id' => $request->account,
            'type' => $request->tnxtype,
            'calc' => $request->tnxtype === LedgerTnxType::PROFIT ? TransactionCalcType::CREDIT : TransactionCalcType::DEBIT,
            'amount' => $request->amount,
            'desc' => ($request->tnxtype === LedgerTnxType::PROFIT) ? 'Profit Adjusted' : (($request->tnxtype === LedgerTnxType::LOSS) ? 'Loss Adjusted' : 'Penalized'),
            'remarks' => $request->remarks,
            'note' => $request->description,
            'uid' => auth()->id(),
            'uname' => auth()->user()->name,
            'method' => 'manual'
        ];

        $this->wrapInTransaction(function ($data) {
            $ivTnx = new IvTransactionService;
            $ivTnx->create($data);
        }, $tnxData);

        return response()->json([
            'msg' => __('Transaction successfully added.'),
            'type' => 'success',
            'reload' => true
        ]);
    }

    public function processPayoutProfit(Request $request)
    {
        if (is_locked('profit')) {
            throw ValidationException::withMessages([
                'error' => __("Sorry, one of your system administrator is working, please try again after few minutes.")
            ]);
        }

        $request->validate([
            'uid' => 'required',
        ], [
            'uid.required' => __("Invalid profit provided.")
        ]);

        $profit = IvProfit::where('id', $request->uid)->whereNull('payout')->first();

        if (blank($profit)) {
            throw ValidationException::withMessages(['error' => __("Invalid profit provided.")]);
        }

        if (!empty($profit->payout)) {
            throw ValidationException::withMessages(['error' => __("Profit is already paid.")]);
        }

        if ($profit->invest->status !== InvestmentStatus::ACTIVE) {
            throw ValidationException::withMessages(['error' => __("Sorry, this investment plan is not active.")]);
        }

        $this->wrapInTransaction(function ($profit) {
            $this->investment->proceedPayout($profit->user_id, [$profit->id], 'manual');
        }, $profit);

        return response()->json([
            'type' => 'success',
            'msg' => __("Profit has been successfully approved."),
            'reload' => true
        ]);
    }
}
