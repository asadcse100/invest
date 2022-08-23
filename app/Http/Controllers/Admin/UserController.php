<?php


namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\UserMeta;
use App\Models\VerifyToken;

use App\Enums\UserStatus;
use App\Enums\UserRoles;
use App\Mail\SystemEmail;
use App\Jobs\ProcessEmail;
use App\Filters\UserFilter;
use App\Services\AuthService;
use App\Services\Exports\UserCsvExport;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @param UserFilter $filter
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function index(UserFilter $filter, $userState = null)
    {
        $usersQuery = User::withTrashed()
            ->withoutSuperAdmin()
            ->orderBy('id', user_meta('user_order', 'desc'))
            ->filter($filter);

        if ($userState) {
            $usersQuery->where('status', $userState);
            $usersQuery->where('role', '<>', 'admin');
        } else {
            $usersQuery->where('role', '<>', 'admin')->whereNotIn('status', [UserStatus::INACTIVE]);
        }

        $users = $usersQuery->paginate(user_meta('user_perpage', 10))->onEachSide(0);

        return view('admin.user.list', compact('users'));
    }

    /**
     * @param UserFilter $filter
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function administrator(UserFilter $filter)
    {
        $usersQuery = User::withTrashed()->orderBy('id', user_meta('user_order', 'desc'))->filter($filter);
        $usersQuery->whereIn('role', [UserRoles::ADMIN, UserRoles::SUPER_ADMIN]);
        $users = $usersQuery->paginate(user_meta('user_perpage', 10))->onEachSide(0);

        return view('admin.user.list', compact('users'));
    }

    public function showUserDetails($id, $type)
    {
        $user = User::with([
            'transactions',
            'activities',
            'miscTnx',
            'accounts',
            'allInvested',
            'referrals.referred'
        ])->find($id);

        if (blank($user)) {
            return redirect()->route('admin.users')->withErrors(['invalid' => __('Sorry, user id may invalid or not available.')]);
        }

        if (!blank($user) && $user->id == auth()->user()->id) {
            return redirect()->route('admin.profile.view');
        }

        if (!in_array($type, ['personal', 'transactions', 'misc', 'investments', 'activities', 'referrals'])) {
            return redirect()->route('admin.users.details', [
                'id' => $user->id,
                'type' => 'personal'
            ])->withErrors([
                'invalid' => __('Sorry, your requested details is not available or invalid.')
            ]);
        }

        return view('admin.user.index', [
            'type' => $type,
            'user' => $user,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.0
     */
    public function saveUser(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:3|max:190',
            'email' => 'required|email|max:190|unique:users',
            'password' => 'nullable|min:6',
        ]);

        DB::beginTransaction();

        try {
            $data = $request->all();
            $data['name'] = strip_tags($data['name']);
            $data['registration_method'] = 'direct';
            $data['password'] = $request->get('password') ?? Str::random(8);
            $verified = $request->get('verified') != 'on';
            $isAdmin = ($request->get('role')==UserRoles::ADMIN);

            $redirect = ($isAdmin) ? route('admin.users.administrator') : route('admin.users');

            $user = $this->authService->createUser($data, $verified);

            if (!$user) {
                throw ValidationException::withMessages(['invalid' => __('Unable to create new account, please try again.')]);
            }

            ProcessEmail::dispatch('user-registration-admin', $user, null, null, $data);

            DB::commit();

            return response()->json(['url' => $redirect, 'msg' => __("New user account added successfully.")]);
        } catch (\Exception $e) {
            DB::rollBack();
            save_mailer_log($e, 'user-registration-admin');
            throw ValidationException::withMessages(['invalid' => __('Unable to create user, please try again.')]);
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.0
     */
    public function updateAction(Request $request)
    {
        $actionType = $request->get('action');

        switch ($actionType) {
            case 'suspend':
                return $this->suspendUser($request);
                break;
            case 'active':
                return $this->activeUser($request);
                break;
            case 'password':
                return $this->resetPassword($request);
                break;
            case 'locked':
                return $this->statusUpdate($actionType, $request);
                break;
            case 'verification':
                return $this->resendVerificationEmail($request);
                break;
        }

        throw ValidationException::withMessages(['invalid' => __('An error occurred. Please try again.'.$actionType)]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.0
     */
    public function resetPassword(Request $request)
    {
        $userID = ($request->get('user_id')) ? (int) $request->get('user_id') : (int) $request->get('uid');
        $isReload = ($request->get('reload') == true || $request->get('reload') == 'true') ? true : false;

        try {
            $user = User::find($userID);
            $password = Str::random(8);

            if ($user) {
                if ($user->role == UserRoles::SUPER_ADMIN) {
                    return response()->json(['type' => 'error', 'msg' => __('Sorry, you do not have enough permissions to reset password the super admin account.')], 202);
                }

                $user->password = Hash::make($password);
                $user->save();

                ProcessEmail::dispatch('users-admin-reset-password', $user, null, null, ['random_pass' => $password]);

                return response()->json(['title' => 'Password Reset', 'msg' => __('The password has been reset successfully.'), 'reload' => $isReload]);
            }
        } catch (\Exception $e) {
            // IO: NEED TO ROLLBACK
            save_mailer_log($e, 'users-admin-reset-password');
            throw ValidationException::withMessages(['invalid' => __('An error occurred. Please try again.')]);
        }
        throw ValidationException::withMessages(['invalid' => __('User not found or invalid user account id.')]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.0
     */
    public function sendEmail(Request $request)
    {
        $userID = ($request->get('send_to')) ? (int) $request->get('send_to') : (int) $request->get('uid');

        $user = User::find($userID);
        if (!blank($user)) {
            $data = [
                "subject" => sanitize_input($request->get('subject')),
                "greeting" => sanitize_input($request->get('greeting')),
                "message" => sanitize_input($request->get('message'))
            ];

            if (isset($data['greeting']) && empty($data['greeting'])) {
                $data['greeting'] = __("Hello");
            }

            if (isset($data['subject']) && empty($data['subject'])) {
                $data['subject'] = __("New message from :site", ['site' => site_info('name')]);
            }

            try {
                Mail::to($user->email)->send(new SystemEmail($data, 'users.custom-email'));
            } catch (\Exception $e) {
                save_mailer_log($e, 'send-email-user');
                throw ValidationException::withMessages(['invalid' => __('Sorry, we are unable to send email to user.')]);
            }

            return response()->json(['title' => 'Message Sent', 'msg' => __('Your message has been sent successfully.')]);
        }

        throw ValidationException::withMessages(['invalid' => __('An error occurred. Please try again.')]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @version 1.0.0
     * @since 1.0
     */
    public function suspendUser(Request $request)
    {
        $userID = ($request->get('user_id')) ? (int) $request->get('user_id') : (int) $request->get('uid');
        $isReload = ($request->get('reload')) ? $request->get('reload') : false;

        $user = User::find($userID);
        if (!blank($user)) {
            if ($user->status == UserStatus::INACTIVE) {
                throw ValidationException::withMessages(['invalid' => __('User account may not verified or inactive.')]);
            }

            if ($user->role == UserRoles::SUPER_ADMIN) {
                return response()->json(['type' => 'error', 'msg' => __('Sorry, you do not have enough permissions to suspend the super admin account.')], 202);
            }

            $user->status = UserStatus::SUSPEND;
            $user->save();
            return response()->json([ 'title' => 'Account Suspended', 'msg' => __('User has been successfully suspended.'), 'state' => __('Suspend'), 'reload' => $isReload ]);
        }

        throw ValidationException::withMessages(['invalid' => __('User not found or invalid user account id.')]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @version 1.0.0
     * @since 1.0
     */
    public function activeUser(Request $request)
    {
        $userID = ($request->get('user_id')) ? (int) $request->get('user_id') : (int) $request->get('uid');
        $isReload = ($request->get('reload')) ? $request->get('reload') : false;

        $user = User::find($userID);
        if (!blank($user)) {
            if ($user->status == UserStatus::INACTIVE) {
                throw ValidationException::withMessages(['invalid' => __('User account may not verified or inactive.')]);
            }
            $user->status = UserStatus::ACTIVE;
            $user->save();
            return response()->json([ 'title' => 'Account Actived', 'msg' => __('User has been successfully actived.'), 'state' => __('Active'), 'reload' => $isReload ]);
        }

        throw ValidationException::withMessages(['invalid' => __('User not found or invalid user account id.')]);
    }

    /**
     * @param $status
     * @param $userID
     * @return \Illuminate\Http\JsonResponse
     * @version 1.0.0
     * @since 1.0
     */
    public function statusUpdate($status, Request $request)
    {
        $uStatus = false;
        $userID = ($request->get('user_id')) ? (int) $request->get('user_id') : (int) $request->get('uid');
        $isReload = ($request->get('reload')) ? $request->get('reload') : false;

        switch ($status) {
            case 'active':
                $uStatus = UserStatus::ACTIVE;
            break;

            case 'suspend':
                $uStatus = UserStatus::SUSPEND;
            break;

            case 'locked':
                $uStatus = UserStatus::LOCKED;
            break;

            case 'deleted':
                $uStatus = UserStatus::DELETED;
            break;
        }

        $user = User::find($userID);
        if (!blank($user) && $uStatus) {
            if ($user->status == UserStatus::INACTIVE) {
                throw ValidationException::withMessages(['invalid' => __('User account may not verified or inactive.')]);
            }

            if ($user->role == UserRoles::SUPER_ADMIN) {
                return response()->json(['type' => 'error', 'msg' => __('Sorry, you do not have enough permissions to :what the super admin account.', ['what' => strtolower(__(ucfirst($status))) ]) ], 202);
            }

            if ($status=='deleted') {
                $user->deleted_at = Carbon::now();
            }
            $user->status = $uStatus;
            $user->save();
            return response()->json([ 'title' => "Status Updated", 'msg' => __("User status has been set ':what'.", ['what' => strtolower(__(ucfirst($status)))]), 'reload' => $isReload ]);
        }

        throw ValidationException::withMessages(['invalid' => __('User not found or invalid user account id.')]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.0
     */
    public function bulkAction(Request $request)
    {
        $this->validate($request, [
            'action' => 'required|string',
            'users' => 'required|array|min:1',
        ]);

        $action = $request->get('action');
        $users = $request->get('users');

        if ($action && $users) {
            if ($action ==='removed') {
                $userQuery = User::whereIn('id', $users)
                    ->where('status', [UserStatus::INACTIVE])
                    ->whereNotIn('role', [UserRoles::SUPER_ADMIN])
                    ->where('id', '<>', auth()->user()->id);
            } else {
                $userQuery = User::whereIn('id', $users)
                    ->whereNotIn('status', [UserStatus::INACTIVE])
                    ->whereNotIn('role', [UserRoles::SUPER_ADMIN])
                    ->where('id', '<>', auth()->user()->id);
            }

            if (!blank($userQuery)) {
                switch ($action) {
                    case 'suspended':
                        $userQuery->update([ 'status' => UserStatus::SUSPEND ]);
                        break;
                    case 'locked':
                        $userQuery->update([ 'status' => UserStatus::LOCKED ]);
                        break;
                    case 'actived':
                        $userQuery->update([ 'status' => UserStatus::ACTIVE ]);
                        break;
                    case 'deleted':
                        $userQuery->update([ 'status' => UserStatus::DELETED, 'deleted_at' => Carbon::now() ]);
                        break;
                    case 'removed':
                        $ids = $userQuery->pluck('id')->toArray();
                        $userQuery->forceDelete();
                        UserMeta::whereIn('user_id', $ids)->delete();
                        break;
                }
                return response()->json([ 'title' => 'Bulk Updated', 'msg' => __('All the selected users has been :what.', ['what' => __($action)]), 'reload' => true ]);
            }
            return response()->json([ 'type' => 'info', 'msg' => __('Failed to update the selected users.') ]);
        }
        throw ValidationException::withMessages(['invalid' => __('An error occurred. Please try again.')]);
    }

    public function resendVerificationEmail(Request $request)
    {
        $userId = $request->get('uid');
        $user = User::WithoutSuperAdmin()->find($userId);

        if (blank($user) || $user->status != UserStatus::INACTIVE) {
            throw ValidationException::withMessages(['invalid' => __('Invalid User!')]);
        }

        try {
            $this->authService->generateNewToken($user, true);
            $user = $user->fresh();
            ProcessEmail::dispatch('users-confirm-email', $user);
        } catch (\Exception $e) {
            save_mailer_log($e, 'users-confirm-email');
            return response()->json(['type' => 'error', 'msg' => __('Sorry new verification email sending failed!') ], 202);
        }

        return response()->json([ 'title' => 'Verification Email Sent', 'msg' => __('A new verification email has been sent to user!')]);
    }

    /**
     * @param Request $request
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.1.2
     */
    public function exportUsers(Request $request)
    {
        if (is_demo_user()) {
            return back()->with(['warning' => 'Sorry, you do not have enough permissions.' ]);
        }

        $request->validate([
            'type' => 'required|in:entire,minimum,compact'
        ]);

        $export = new UserCsvExport;
        $export->download($request->type);
    }

    /**
     * @param Request $request
     * @throws ValidationException
     * @version 1.0.0
     * @since 1.1.2
     */
    public function getReferralTree(Request $request)
    {
        $userId = $request->get('id');
        $user = User::with(['referrals.referred'])->find($userId);
        if (!blank($user)) {
            return view('admin.user.referral-tree.tree', ['user' => $user])->render();
        }
    }
}
