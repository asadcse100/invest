<?php


namespace App\Services;

use App\Models\User;
use App\Models\UserMeta;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProfileService extends Service
{
    /**
     * @param $data
     * @version 1.0.0
     * @since 1.0
     */
    private function updateUserInfo($data)
    {
        if (empty($data)) {
            return;
        }

        $user = User::find(auth()->user()->id);
        $user->update($data);
    }

    /**
     * @param $data
     * @version 1.0.0
     * @since 1.0
     */
    private function updateProfileInfo($data)
    {
        if (empty($data)) {
            return;
        }

        foreach ($data as $key => $value) {
            UserMeta::updateOrCreate([
                'user_id' => auth()->user()->id,
                'meta_key' => $key
            ], ['meta_value' => strip_tags($value) ?? null]);
        }
    }

    /**
     * @param Request $request
     * @version 1.0.0
     * @since 1.0
     */
    public function savePersonalInfo(Request $request)
    {
        if (profile_lockable()) {
            $request->request->remove('name');
            $request->request->remove('profile_dob');
            $request->request->remove('profile_gender');
        }
    
        $userInfo = $request->only(['name']);
        $this->updateUserInfo($userInfo);

        $profileInfo = $request->only(['profile_phone', 'profile_dob', 'profile_telegram', 'profile_display_name', 'profile_gender']);
        $profileInfo['profile_display_full_name'] = $request->get('profile_display_full_name', 'off');
        $this->updateProfileInfo($profileInfo);
    }

    /**
     * @param Request $request
     * @version 1.0.0
     * @since 1.0
     */
    public function saveAddressInfo(Request $request)
    {
        if (profile_lockable()) {
            throw ValidationException::withMessages(['error' => __("If you'd like to update your address, please re-submit your address verification documents.")]);
        }

        $addressInfo = $request->only([
            'profile_address_line_1',
            'profile_address_line_2',
            'profile_city',
            'profile_state',
            'profile_zip',
            'profile_country',
            'profile_nationality',
        ]);

        $this->updateProfileInfo($addressInfo);
    }

    public function completeProfile($username, $data)
    {
        auth()->user()->update(['username' => $username]);

        $this->updateProfileInfo($data);
    }
}
