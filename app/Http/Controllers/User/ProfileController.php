<?php


namespace App\Http\Controllers\User;

use App\Models\UserMeta;
use App\Services\ProfileService;
use App\Services\AuthService;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
use App\Jobs\ProcessEmail;
use App\Models\User;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;


class ProfileController extends Controller
{
    private $profileService;
    private $authService;
    private $settingsService;

    public function __construct(
        ProfileService $profileService,
        AuthService $authService,
        SettingsService $settingsService
    ) {
        $this->profileService = $profileService;
        $this->authService = $authService;
        $this->settingsService = $settingsService;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function View()
    {
        $metas = UserMeta::where('user_id', auth()->user()->id)->pluck('meta_value', 'meta_key');
        $countries = filtered_countries();

        return view('user.account.profile', compact('metas', 'countries'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function welcome()
    {
        $user = auth()->user();
        $countries = filtered_countries();

        return $user->has_basic ? redirect()->route('dashboard') : view('user.profile-welcome', compact('user', 'countries'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function congrats()
    {
        $user = auth()->user();

        return view('user.profile-completed', compact('user'));
    }

    /**
     * @param ProfileRequest $request
     * @version 1.0.0
     * @since 1.0
     */
    public function savePersonalInfo(ProfileRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->profileService->savePersonalInfo($request);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['error' => __('Failed to update profile.')]);
        }

        return response()->json(['title' => 'Profile Updated', 'msg' => __('Personal info has been successfully updated.')]);
    }

    /**
     * @param AddressRequest $request
     * @version 1.0.0
     * @since 1.0
     */
    public function saveAddressInfo(AddressRequest $request)
    {
        $this->profileService->saveAddressInfo($request);
        return response()->json(['title' => 'Profile Updated', 'msg' => __('Address info has been successfully updated.')]);
    }

    /**
     * @version 1.0.0
     * @since 1.0
     */
    public function completeProfile(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:5|unique:users,username,' . auth()->id(),
            'profile_display_name' => 'required|string',
            'profile_phone' => 'required|string',
            'profile_display_full_name' => 'string',
            'profile_dob' => 'required|date_format:m/d/Y',
            'profile_country' => 'required|string'
        ], [
            'username.required' => __("Please enter a valid username."),
            'username.unique' => __("Someone already has that username."),
            'profile_display_name.required' => __("Please enter a nice name"),
            'profile_phone.required' => __("Please enter your phone number"),
            'profile_dob.required' => __("Please set your date of birth."),
            'profile_dob.date_format' => __("Enter date of birth in this 'mm/dd/yyyy' format."),
            'profile_country.required' => __("Please choose your country.")
        ]);

        $metaData = $request->only(['profile_phone', 'profile_display_name', 'profile_display_full_name', 'profile_dob', 'profile_country', 'profile_gender']);

        if (array_key_exists('profile_display_full_name', $metaData)) {
            $metaData['profile_display_full_name'] = 'off';
        }

        $this->profileService->completeProfile($request->username, $metaData);

        return redirect()->route('account.congrats');
    }

    public function validateUsername(Request $request)
    {
        $uname = strlen($request->get('username'));

        try {
            $request->validate([
                'username' => 'bail|required|string|min:5|unique:users,username,' . auth()->id()
            ]);
            return response()->json(['error' => false, 'note' => __("The username valid to use.")]);
        } catch (ValidationException $e) {
            $note = ($uname < 5) ? __('The username is not valid.') : __('The username is not available.');
            return response()->json(['error' => true, 'note' => $note]);
        }
    }

    /**
     * @version 1.0.0
     * @since 1.0
     */
    public function updateUnverifiedEmail(Request $request)
    {
        $this->validate($request, [
            'user_new_unverified_email' => "required|email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,9}$/ix|max:190|not_in:" . auth()->user()->email . "|unique:users,email," . auth()->user()->id,
        ], [
            'user_new_unverified_email.not_in' => __("The new email address cannot be the same as your current email address."),
            'user_new_unverified_email.unique' => __("The chosen email is already registered with us. Please use a different email address."),
            'user_new_unverified_email.regex' => __("Please enter a valid email address."),
        ]);

        $emailMetaCount = $this->settingsService->emailMetaCount($request->user_new_unverified_email);
        if ($emailMetaCount > 0) {
            throw ValidationException::withMessages(['user_new_unverified_email' => __("The chosen email is already registered with us. Please use a different email address.")]);
        }

        $user = User::find(auth()->user()->id);
        $user->email = $request->user_new_unverified_email;
        $user->save();

        $this->authService->generateNewToken($user);
        ProcessEmail::dispatch('users-confirm-email', $user);
        return response()->json(['title' => 'Email Updated', 'msg' => __('Email address has been successfully updated and verification link has been sent.')]);
    }

    public function verifyUnverifiedEmail($user)
    {
        $user = User::find($user);
        $this->authService->generateNewToken($user);
        ProcessEmail::dispatch('users-confirm-email', $user);
        return back()->with(['email-sent' => __('Email verification link has been sent.')]);
    }
}
