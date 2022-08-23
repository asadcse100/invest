<?php

namespace App\Services;

use App\Enums\UserRoles;
use App\Enums\UserStatus;

use App\Models\ReferralCode;
use App\Models\Referral;
use App\Models\User;
use Carbon\Carbon;

class ReferralService extends Service
{
    private function getUserByRef($code): ?int
    {
        $referral = ReferralCode::where('code', $code)->first();

        if (blank($referral)) {
            return false;
        }

        $user = User::where('id', $referral->user_id)->where('status', UserStatus::ACTIVE)->where('role', UserRoles::USER)->first();
        return (!blank($user)) ? $user->id : false;
    }

    public function getReferrer()
    {
        return session('nio-refer-by');
    }

    public function getReferCode()
    {
        return session('nio-refer-code');
    }

    public function getReferMeta()
    {
        $meta = [];        
        if ($this->getReferrer()) {
            $meta['by'] = $this->getReferrer(); 
        }
        if ($this->getReferCode()) {
            $meta['code'] = $this->getReferCode(); 
        }

        return (!empty($meta) && $this->getReferrer()) ? $meta : false;
    }

    public function setReferrer($code)
    {
        $refer = $this->getUserByRef($code);

        if (!empty($refer)) {
            session(['nio-refer-by' => $refer]);
            session(['nio-refer-code' => $code]);
        }
        
        if (request()->has('source')) {
            $refid = (int) request()->get('source', 0);
            update_gss('checker', $refid, 'ref');
        }
    }

    public function createReferral($user)
    {
        if (empty($user->refer) || !is_json($user->refer)) {
            return;
        }

        $refer = json_decode($user->refer);
        if(empty($refer->by)) {
            return;
        }

        $referrer = User::find($refer->by);
        if (empty($referrer)) {
            return;
        }

        $meta['at'] = time();
        if (!empty($refer->code)) {
            $meta['code'] = $refer->code;
        }

        $ref = new Referral();
        $ref->user_id = $user->id;
        $ref->refer_by = $referrer->id;
        $ref->join_at = $user->created_at;
        $ref->meta = (!empty($meta)) ? $meta : [];
        $ref->save();

        $user->refer = $referrer->id;
        $user->save();

        session(['nio-refer-by' => '']);
        session(['nio-refer-code' => '']);
    }
}
