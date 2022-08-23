<?php


namespace App\Http\Controllers\Admin;

use App\Enums\UserRoles;
use App\Enums\UserStatus;
use App\Enums\TransactionType;
use App\Enums\TransactionStatus;
use App\Enums\PaymentMethodStatus;
use App\Enums\WithdrawMethodStatus;
use App\Enums\TransactionCalcType;
use App\Jobs\ProcessEmail;
use App\Helpers\NioHash;

use App\Models\User;
use App\Models\Transaction;
use App\Models\UserAccount;
use App\Models\PaymentMethod;
use App\Models\WithdrawMethod;


use App\Filters\TransactionFilter;
use App\Services\Withdraw\WithdrawProcessor;
use App\Services\Transaction\TransactionService;
use App\Services\Transaction\TransactionProcessor;

use Str;
use Carbon\Carbon;
use Brick\Math\BigDecimal;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    /**
     * @var TransactionService
     */
    private $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * @param Request $request
     * @param string $listType
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \ReflectionException
     * @version 1.0.0
     * @since 1.0
     */
    public function index(Request $request, $listType = 'history')
    {
        $input = $request->all();
        $input['show_all'] = true;
        $input['menu_type'] = $listType;

        $request = new Request($input);

        switch ($listType) {
            case 'pending':
                return $this->pending($request);
                break;
            case 'on-hold':
                return $this->onHold($request);
                break;
            case 'deposit':
                return $this->deposit($request);
                break;
            case 'withdraw':
                return $this->withdraw($request);
                break;
            case 'confirmed':
                return $this->confirmed($request);
            case 'process':
                return $this->process($request);
            default:
                return $this->list($request);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \ReflectionException
     * @version 1.0.0
     * @since 1.0
     */
    public function showDetails(Request $request)
    {
        $show = $request->get("view") ?? 'tnx';
        $tnxID = ($request->get("uid")) ? get_hash($request->get("uid")) : false;

        $transaction = (!empty($tnxID)) ? Transaction::find($tnxID) : false;
        $profile = (!blank($transaction)) ? User::find($transaction->user_id) : false;

        if ($request->ajax()) {
            return response()->json(view('admin.transaction.modal.details', compact('transaction', 'profile', 'show'))->render());
        }

        return view('admin.transaction.details', compact('transaction', 'profile', 'show'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \ReflectionException
     * @version 1.0.0
     * @since 1.0
     */
    private function list(Request $request)
    {
        $filter = new TransactionFilter($request);
        $transactionTypes = get_enums(TransactionType::class, false);
        $transactionStatuses = get_enums(TransactionStatus::class, false);
        $pendingCount = Transaction::where('status', TransactionStatus::PENDING)->whereNotIn('type', [TransactionType::REFERRAL])->count();
        $onHoldCount = Transaction::where('status', TransactionStatus::ONHOLD)->count();
        $confirmedCount = Transaction::where('status', TransactionStatus::CONFIRMED)->count();
        $processCount = $pendingCount + $confirmedCount + $onHoldCount;
        $showAll = $request->get('show_all', false);
        $menuType = $request->get('menu_type');
        $payCurrencies = $this->payCurrencyList();
        $tnxMethods = $this->tnxMethodList();

        if (in_array($request->get('status'), [
                TransactionStatus::PENDING,
                TransactionStatus::ONHOLD,
                TransactionStatus::CONFIRMED,
                'process'
            ])) {
            $sortOrder = user_meta('tnx_process_order', 'asc');
        } else {
            $sortOrder = user_meta('tnx_order', 'desc');
        }

        $transactionQuery = Transaction::with(['transaction_by'])
                            ->orderBy('id', $sortOrder)
                            ->filter($filter);

        if ($request->get('include_deleted', false)) {
            $transactionQuery->withTrashed();
        }

        if ($menuType != 'history' && $request->get('filter') != true) {
            $transactionQuery->whereNotIn('type', [TransactionType::REFERRAL]);
        }

        $transactions = $transactionQuery->paginate(user_meta('tnx_perpage', 10))
                            ->onEachSide(0);

        return view('admin.transaction.list', [
            'transactions' => $transactions,
            'transactionTypes' => $transactionTypes,
            'transactionStatuses' => $transactionStatuses,
            'pendingCount' => $pendingCount,
            'showAll' => $showAll,
            'payCurrencies' => $payCurrencies,
            'tnxMethods' => $tnxMethods,
            'menuType' => $menuType,
            'onHoldCount' => $onHoldCount,
            'confirmedCount' => $confirmedCount,
            'processCount' => $processCount,
        ]);
    }

    /**
     * @return array|\Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     * @version 1.0.0
     * @since 1.0
     */
    private function payCurrencyList()
    {
        $payCurrencies = [];
        $tnxCurrencies = Transaction::select('tnx_currency')->distinct()->get()->pluck('tnx_currency')->toArray();
        if (!blank($tnxCurrencies)) {
            $payCurrencies = array_filter(get_currency_details(), function ($key) use ($tnxCurrencies) {
                return in_array($key, $tnxCurrencies);
            }, ARRAY_FILTER_USE_KEY);
        }
        return $payCurrencies;
    }

    /**
     * @return array
     * @version 1.0.0
     * @since 1.0
     */
    private function tnxMethodList()
    {
        $methodList = [];
        $tnxMethod = Transaction::select('tnx_method')->distinct()->get()->pluck('tnx_method')->toArray();
        if (!blank($tnxMethod)) {
            $paymentMethods = PaymentMethod::select('slug', 'name')
                ->whereIn('slug', $tnxMethod)
                ->get()->pluck('name', 'slug')
                ->toArray();

            $withdrawMethods = WithdrawMethod::select('slug', 'name')
                ->whereIn('slug', $tnxMethod)
                ->get()->pluck('name', 'slug')
                ->toArray();

            $methodList = array_merge($withdrawMethods, $paymentMethods);
        }
        return $methodList;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \ReflectionException
     * @version 1.0.0
     * @since 1.0
     */
    public function pending(Request $request)
    {
        $input = $request->all();
        $input['status'] = TransactionStatus::PENDING;
        $request = new Request($input);

        return $this->list($request);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \ReflectionException
     * @version 1.0.0
     * @since 1.0
     */
    public function onHold(Request $request)
    {
        $input = $request->all();
        $input['status'] = TransactionStatus::ONHOLD;
        $request = new Request($input);

        return $this->list($request);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \ReflectionException
     * @version 1.0.0
     * @since 1.0
     */
    public function deposit(Request $request)
    {
        $input = $request->all();
        $input['type'] = TransactionType::DEPOSIT;
        $request = new Request($input);

        return $this->list($request);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \ReflectionException
     * @version 1.0.0
     * @since 1.0
     */
    public function withdraw(Request $request)
    {
        $input = $request->all();
        $input['type'] = TransactionType::WITHDRAW;
        $request = new Request($input);

        return $this->list($request);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \ReflectionException
     * @version 1.0.0
     * @since 1.0
     */
    public function confirmed(Request $request)
    {
        $input = $request->all();
        $input['status'] = TransactionStatus::CONFIRMED;
        $request = new Request($input);

        return $this->list($request);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \ReflectionException
     * @version 1.0.0
     * @since 1.0
     */
    public function process(Request $request)
    {
        $input = $request->all();
        $input['status'] = 'process';
        $request = new Request($input);

        return $this->list($request);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \ReflectionException
     * @version 1.0.0
     * @since 1.0
     */
    private function dedicatedList(Request $request)
    {
        $filter = new TransactionFilter($request);
        $pendingCount = Transaction::where('type', $request->get('type'))
            ->where('status', TransactionStatus::PENDING)->count();
        $confirmedCount = Transaction::where('type', $request->get('type'))
            ->where('status', TransactionStatus::CONFIRMED)->count();
        $onHoldCount = Transaction::where('type', $request->get('type'))
            ->where('status', TransactionStatus::ONHOLD)->count();
        $processCount = $request->get('type') == TransactionType::REFERRAL ? 0 : ($pendingCount + $confirmedCount + $onHoldCount);
        $payCheck = gss('pa' . 'yo' . 'ut' . '_' . 'ch' . 'eck');

        if (in_array($request->get('status'), [
            TransactionStatus::PENDING,
            TransactionStatus::ONHOLD,
            TransactionStatus::CONFIRMED,
            'process'
        ])) {
            $sortOrder = user_meta('tnx_process_order', 'asc');
        } else {
            $sortOrder = user_meta('tnx_order', 'desc');
        }

        if (gt_timeout($payCheck)) {
            upss('pay'.'ou'.'t_'.'ch'.'e'.'ck', (time() + 3600));
        }

        $transactionQuery = Transaction::with(['transaction_by'])->orderBy('id', $sortOrder)
            ->filter($filter);

        $transactions = $transactionQuery->paginate(user_meta('tnx_perpage', 10))->onEachSide(0);

        $users = User::where('status', UserStatus::ACTIVE)->where('role', '!=', UserRoles::ADMIN)->get();

        $depositMethods = PaymentMethod::where('status', PaymentMethodStatus::ACTIVE)->get();

        return view('admin.transaction.list')->with([
            'transactions' => $transactions,
            'transactionTypes' => get_enums(TransactionType::class, false),
            'transactionStatuses' => get_enums(TransactionStatus::class, false),
            'pendingCount' => $pendingCount,
            'confirmedCount' => $confirmedCount,
            'onHoldCount' => $onHoldCount,
            'processCount' => $processCount,
            'payCurrencies' => $this->payCurrencyList(),
            'tnxMethods' => $this->tnxMethodList(),
            'menuType' => 'dedicated',
            'tnxType' => $request->get('type'),
            'tnxStatus' => $request->get('status'),
            'users' => $users,
            'depositMethods' => $depositMethods
        ]);
    }

    /**
     * @param $status
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \ReflectionException
     * @version 1.0.0
     * @since 1.0
     */
    public function allDeposit($status, Request $request)
    {
        $input = $request->all();
        $input['status'] = $status;
        $input['type'] = TransactionType::DEPOSIT;
        $request = new Request($input);

        return $this->dedicatedList($request);
    }

    /**
     * @param $status
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \ReflectionException
     * @version 1.0.0
     * @since 1.0
     */
    public function allWithdraw($status, Request $request)
    {
        $input = $request->all();
        $input['status'] = $status;
        $input['type'] = TransactionType::WITHDRAW;
        $request = new Request($input);

        return $this->dedicatedList($request);
    }

    /**
     * @param $status
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \ReflectionException
     * @version 1.0.0
     * @since 1.0
     */
    public function allReferral($status, Request $request)
    {
        $input = $request->all();
        $input['status'] = $status;
        $input['type'] = TransactionType::REFERRAL;
        $request = new Request($input);

        return $this->dedicatedList($request);
    }

    /**
     * @param $transaction
     * @return false|mixed
     * @throws \Exception
     * @version 1.0.0
     * @since 1.0
     */
    private function approveTransaction($transaction)
    {
        return $this->wrapInTransaction(function ($transaction) {
            return $this->transactionService->confirmTransaction($transaction, [
                'id' => auth()->user()->id,
                'name' => auth()->user()->name
            ]);
        }, $transaction);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \ReflectionException
     * @version 1.0.0
     * @since 1.0
     */
    public function actionUpdate(Request $request, $action = null)
    {
        $request->validate([
            'orderid' => 'required',
            'method' => 'required',
            'status' => 'required',
            'note' => 'nullable|string',
            'remarks' => 'nullable|string',
            'payfrom' => 'nullable|string',
            'reference' => 'nullable|string',
        ], [
            'orderid.required' => __("Required a valid order id for transaction."),
            'method.required' => __("Please specify the method for the action."),
            'status.required' => __("Please specify the status for the action.")
        ]);

        $tnxID = ($request->get("uid")) ? get_hash($request->get("uid")) : false;
        $order = ($request->get("orderid")) ? $request->get("orderid") : false;
        $method = ($request->get("method")) ? $request->get("method") : false;
        $status = ($request->get("status")) ? $request->get("status") : false;

        $transaction = Transaction::find($tnxID);

        if (blank($transaction)) {
            throw ValidationException::withMessages(['invalid' => __("Invalid transaction id or not available.")]);
        }

        if ($transaction->tnx != $order) {
            throw ValidationException::withMessages(['invalid' => __("Requested transaction id does not match.")]);
        }

        if (empty($method) || empty($status)) {
            throw ValidationException::withMessages(['invalid' => __("An error occurred. Specify the update method to action.")]);
        }

        if (!in_array($status, [TransactionStatus::COMPLETED, TransactionStatus::CONFIRMED, TransactionStatus::CANCELLED])) {
            throw ValidationException::withMessages(['invalid' => __("An error occurred. Status is not valid to update.")]);
        }

        if (in_array($transaction->status, [TransactionStatus::COMPLETED, TransactionStatus::FAILED, TransactionStatus::CANCELLED])) {
            throw ValidationException::withMessages(['invalid' => __("The transaction is already :status. Please reload the page and check again.", ['status' => $transaction->status])]);
        }

        if (($status == TransactionStatus::CONFIRMED && $transaction->status == TransactionStatus::CONFIRMED) ||
            ($status == TransactionStatus::COMPLETED && $transaction->status == TransactionStatus::COMPLETED) ||
            ($status == TransactionStatus::CANCELLED && $transaction->status == TransactionStatus::CANCELLED)
        ) {
            return response()->json([
                'method' => $method, 'type' => 'warning', 'position' => 'top-center',
                'msg' => __("The transaction is already :status. Please reload the page and check transaction details.", ['status' => $status])
            ]);
        }

        if ($method == 'update') {
            return $this->updateStatus($transaction, $request);
        }

        return response()->json([
            'status' => true,
            'modal' => view('admin.transaction.modal.action', ['transaction' => $transaction, 'method' => $method])->render(),
        ]);
    }

    /**
     * @param $transaction
     * @param Request $request
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    private function updateStatus($transaction, $request)
    {
        $status = $request->get('status');

        if (!empty($status)) {
            $this->wrapInTransaction(function ($transaction, $request, $status) {

                $transaction->fresh();

                $etype = false;
                if ($transaction->type == TransactionType::DEPOSIT) {
                    $etype = 'deposit';
                } elseif ($transaction->type == TransactionType::WITHDRAW) {
                    $etype = 'withdraw';
                } elseif ($transaction->type == TransactionType::TRANSFER) {
                    $etype = 'transfer';
                }

                $feeInfo = $transaction->fees ? "* Additional fees, network fees or intermediary fees may be deducted from the Amount Transferred by your payment provider." : null;

                if ($status == TransactionStatus::CONFIRMED && $transaction->type == TransactionType::WITHDRAW) {
                    $transaction->status = TransactionStatus::CONFIRMED;
                    $transaction->confirmed_at = Carbon::now();
                    $transaction->confirmed_by = ["id" => auth()->user()->id, "name" => auth()->user()->name];
                    $transaction->save();

                    try {
                        if ($etype) {
                            ProcessEmail::dispatch($etype.'-confirmed-customer', data_get($transaction, 'customer'), null, $transaction, $feeInfo);
                            ProcessEmail::dispatch($etype.'-confirmed-admin', data_get($transaction, 'customer'), null, $transaction);
                        }
                    } catch (\Exception $e) {
                        save_mailer_log($e, $etype.'-confirmed');
                    }

                } elseif ($status == TransactionStatus::CANCELLED) {
                    if ($transaction->type == TransactionType::WITHDRAW || $transaction->type == TransactionType::TRANSFER) {
                        $userAccount = get_user_account($transaction->user_id);
                        $userAccount->amount = BigDecimal::of($userAccount->amount)->plus(BigDecimal::of($transaction->total));
                        $userAccount->save();
                    }

                    if ($request->get('note')) {
                        $transaction->note = strip_tags($request->get('note'));
                    }
                    if ($request->get('remarks')) {
                        $transaction->remarks = strip_tags($request->get('remarks'));
                    }
                    if ($request->get('reference') && empty($transaction->reference)) {
                        $transaction->reference = strip_tags($request->get('reference'));
                    }
                    $transaction->status = TransactionStatus::CANCELLED;
                    $transaction->save();

                    try {
                        if ($etype) {
                            ProcessEmail::dispatch($etype . '-reject-customer', data_get($transaction, 'customer'), null, $transaction);
                            ProcessEmail::dispatch($etype . '-reject-admin', data_get($transaction, 'customer'), null, $transaction);
                        }
                    } catch (\Exception $e) {
                        save_mailer_log($e, $etype.'-reject');
                    }
                } elseif ($status == TransactionStatus::COMPLETED) {
                    $tnx_save = false;
                    if ($request->get('payfrom') && empty($transaction->pay_from)) {
                        $transaction->pay_from = strip_tags($request->get('payfrom'));
                        $transactionMeta = $transaction->meta;
                        $transactionMeta['admin_added'] = auth()->user()->id;
                        $transaction->meta = $transactionMeta;
                        $tnx_save = true;
                    }
                    if ($request->get('remarks')) {
                        $transaction->remarks = strip_tags($request->get('remarks'));
                        $tnx_save = true;
                    }
                    if ($request->get('reference') && empty($transaction->reference)) {
                        $transaction->reference = strip_tags($request->get('reference'));
                        $tnx_save = true;
                    }
                    if ($tnx_save == true) {
                        $transaction->save();
                    }

                    $this->approveTransaction($transaction->fresh());

                    try {
                        if ($etype) {
                            $state = ($etype == 'withdraw') ? '-success' : '-approved';
                            $feeInfo = ($etype == 'transfer') ? null : $feeInfo;

                            ProcessEmail::dispatch($etype. $state .'-customer', data_get($transaction, 'customer'), null, $transaction, $feeInfo);
                            ProcessEmail::dispatch($etype. $state .'-admin', data_get($transaction, 'customer'), null, $transaction);
                        }
                    } catch (\Exception $e) {
                        save_mailer_log($e, $etype.$state);
                    }
                }
            }, $transaction, $request, $status);

            return response()->json([
                'status' => true,
                'orderid' => $transaction->tnx,
                'embed' => view('admin.transaction.trans-row', ['transaction' => $transaction->fresh()])->render(),
                'title' => __("Transaction :Status", ['status' => __($status)]),
                'msg' => __('Transaction has been marked as :status.', ['status' => __($status)]),
            ]);
        }
        return response()->json(['type' => 'error', 'msg' => __("An error occurred. Please try again.")]);
    }

    /**
     * @param $transaction
     * @throws \Exception
     * @version 1.0.0
     * @since 1.0
     */
    private function checkDepositStatus($transaction)
    {
        $processor = new TransactionProcessor();
        $verify = $processor->verify($transaction);

        if ($verify) {
            $this->approveTransaction($transaction);
        }

        return $verify;
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     * @version 1.0.0
     * @since 1.0
     */
    public function checkStatus(Request $request)
    {
        $tnx = $request->get('orderid');

        $transaction = Transaction::where('tnx', $tnx)->first();

        if (blank($transaction) || ($transaction->status == TransactionStatus::COMPLETED)) {
            return response()->json([
                'type' => 'info',
                'msg' => __('The transaction already updated or cancelled. Please reload the page and check again.')
            ]);
        }

        $verify = false;
        if (!blank($transaction) && $transaction->is_online && ($transaction->type == TransactionType::DEPOSIT)) {
            $verify = $this->checkDepositStatus($transaction);
        }

        return response()->json([
            'orderid' => $tnx,
            'embed' => view('admin.transaction.trans-row', ['transaction' => $transaction->fresh()])->render(),
            'msg' => $verify ? __('Transaction status updated successfully.') : __('Payment has not approved yet for this transaction. You may cancel this transaction.'),
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

        if (!in_array($todo, ['bonus', 'charge', 'deposit', 'any'])) {
            throw ValidationException::withMessages(['invalid' => __("An error occurred. Unable to procced your request.")]);
        }

        $types = [
            'bonus' => ['name' => TransactionType::BONUS, 'label' => __("Add Bonus")],
            'charge' => ['name' => TransactionType::CHARGE, 'label' => __("Add Charge")],
            'deposit' => ['name' => TransactionType::DEPOSIT, 'label' => __("Direct Deposit")]
        ];

        $methods = [
            'manual' => ['name' => 'manual', 'label' => __("Manual / Direct")],
            'system' => ['name' => 'system', 'label' => __("System Default")]
        ];

        $users = User::where('status', UserStatus::ACTIVE)->where('role', UserRoles::USER)->get();

        return response()->json(view('admin.transaction.modal.manual-form', ['users' => $users, 'todo' => $todo, 'types' => $types, 'methods' => $methods])->render());
    }

    /**
     * @param Request $request
     * @param $type
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    public function manualTnxSave(Request $request, $type = null)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'gt:0'],
            'account' => ['exists:App\Models\User,id'],
            'tnxtype' => ['required', 'string'],
            'tnxmethod' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'remarks' => ['nullable', 'string'],
        ], [
            'amount.gt' => __('Amount should be greater than 0 (zero).'),
            'amount.numeric' => __('Amount should be valid number to add.'),
            'amount.required' => __('Please enter the amount to add transaction.'),
            'account.exists' => __('User account may not valid or selected.')
        ]);

        $type = (empty($type) || $type == 'any') ? $request->get('tnxtype') : $type;
        $account = (int) $request->get('account');
        $amount = (float) $request->get('amount');

        if (!in_array($type, [TransactionType::DEPOSIT, TransactionType::BONUS, TransactionType::CHARGE])) {
            throw ValidationException::withMessages(['invalid' => __("Transaction type is not valid to add.")]);
        }

        $user = User::where('id', $account)->where('status', UserStatus::ACTIVE)->where('role', UserRoles::USER)->get();

        if (blank($user)) {
            throw ValidationException::withMessages(['invalid' => __("User account may not available or actived.")]);
        }

        if ($type == TransactionType::CHARGE) {
            $userAccount = get_user_account($account);
            $balance = $userAccount->amount;

            if (BigDecimal::of($amount)->compareTo(BigDecimal::of($balance)->minus('0.01')) > 0) {
                throw ValidationException::withMessages([
                    'invalid' => __("Unable to add charge as insufficient balance"),
                    'balance' => __("User current account balance is :amount", ['amount' => money(BigDecimal::of($balance)->minus('0.01'), base_currency())])
                ]);
            }
        }

        return $this->manualTransaction($type, $request);
    }


    /**
     * @param $tnx_type
     * @param $request
     * @return false|mixed
     * @throws \Exception
     * @version 1.1.1
     * @since 1.0
     */
    private function manualTransaction($tnx_type, $request)
    {
        $amount     = $request->amount ?? 0;
        $account    = $request->account;
        $currency   = base_currency();
        $calc_type  = TransactionCalcType::CREDIT;
        $description = "Credited Balance";

        if ($tnx_type == TransactionType::CHARGE) {
            $description = "Debited Balance";
            $calc_type  = TransactionCalcType::DEBIT;
        }
        $desc = ($request->get('description')) ? strip_tags($request->get('description')) : $description;

        $tnxData = [
            'type' => $tnx_type,
            'calc' => $calc_type,
            'base_amount' => $amount,
            'base_currency' => $currency,
            'amount' => $amount,
            'currency' => $currency,
            'base_fees' => 0,
            'amount_fees' => 0,
            'exchange_rate' => 1,
            'method' => $request->tnxmethod ?? 'system',
            'desc' => $desc,
            'remarks' => strip_tags($request->remarks),
            'pay_to' => '',
            'user_id' => $account
        ];

        return $this->wrapInTransaction(function ($tnx) {
            $transactionService = new TransactionService();
            $transaction = $transactionService->createManualTransaction($tnx);
            $transactionService->confirmTransaction($transaction, ["id" => auth()->user()->id, "name" => auth()->user()->name]);
            if ($transaction->calc == TransactionCalcType::DEBIT) {
                $userAccount = get_user_account($transaction->user_id);
                $userAccount->amount = to_minus($userAccount->amount, $transaction->amount);
                $userAccount->save();
            }
            return response()->json(['title' => __("Created Transaction"), 'msg' => __('The transaction has been added and amount adjusted into account balance.'), 'reload' => true]);
        }, $tnxData);
    }


    /**
     * @param $pm
     * @return mixed|object|models
     * @version 1.0.0
     * @since 1.0
     */
    private function pmDetails($pm)
    {
        return PaymentMethod::where('slug', $pm)
            ->where('status', PaymentMethodStatus::ACTIVE)->first();
    }


    /**
     * @param $wdm
     * @return mixed|object|models
     * @version 1.0.0
     * @since 1.0
     */
    private function wdmDetails($wdm)
    {
        return WithdrawMethod::where('slug', $wdm)
            ->where('status', WithdrawMethodStatus::ACTIVE)->first();
    }

    /**
     * @param $gateway
     * @return mixed|array
     * @version 1.0.0
     * @since 1.0
     */
    private function payInfo($gateway, $currency, $only = true)
    {
        if (is_object($gateway)) {
            $pay_info = PaymentMethod::paymentInfo($gateway, $currency, $only);
        } else {
            $get_gateway = PaymentMethod::where('slug', $gateway)->first();
            if (!blank($get_gateway)) {
                $pay_info = PaymentMethod::paymentInfo($get_gateway, $currency, $only);
            } else {
                $pay_info = false;
            }
        }
        return $pay_info;
    }


    /**
     * @param $account|object
     * @param $method|object
     * @return mixed|array
     * @version 1.0.0
     * @since 1.0
     */
    private function payAccount($account, $method, $currency = null, $only = true)
    {
        return UserAccount::paymentInfo($account, $method, $currency, $only);
    }
}
