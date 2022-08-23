<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TrustRequests
{
    private $rr = [
        'admin.transaction.update' => [ "inpt" => ["method", "update"] ],
        'admin.quick.register' => [ "inpt" => ["state", "revoke"] ],
        'admin.transaction.manual.save' => true,
        'admin.save.app.settings' => true,
        'admin.save.website.brands' => true,
        'admin.users.save' => true,
        'admin.users.action' => true,
        'admin.users.action.bulk' => true,
        'admin.users.send.email' => true,
        'admin.profile.settings.save' => true,
        'admin.profile.update.address' => true,
        'admin.profile.update.personal' => true,
        'admin.profile.settings.change.email' => true,
        'admin.profile.settings.change.password' => true,
        'admin.profile.settings.change.email.resend' => true,
        'admin.profile.settings.change.email.cancel' => true,
        'admin.profile.settings.2fa' => true,
        'admin.profile.activity.clear' => true,
        'admin.profile.activity.delete' => true,
        'admin.manage.pages.save' => true,
        'admin.manage.pages.delete' => true,
        'admin.manage.email.template.save' => true,
        'admin.manage.email.template.test' => true,
        'admin.system.langs.add' => true,
        'admin.system.langs.update' => true,
        'admin.system.langs.delete' => true,
        'admin.system.langs.process.action' => true,
        'admin.settings.email.test' => true,
        'admin.settings.component.kyc.update' => true,
        'admin.settings.component.fund-transfer.update' => true,
        'admin.settings.component.cron.nio-cron.save' => true,
        'admin.settings.gateway.quick' => true,
        'admin.settings.gateway.payment.*.save' => true,
        'admin.settings.gateway.withdraw.*.save' => true,
        'admin.settings.investment.save' => true,
        'admin.investment.process.profits.payout' => true,
        'admin.investment.process.profit.payout' => true,
        'admin.investment.process.plans.sync' => true,
        'admin.investment.scheme.save' => true,
        'admin.investment.scheme.status' => true,
        'admin.investment.scheme.update' => true,
        'admin.investment.plan.complete' => true,
        'admin.investment.plan.approve' => true,
        'admin.investment.plan.cancel' => true,
        'admin.investment.manual.save' => true,
        'admin.kyc.update' => true,
    ];

    private $srr = [
        'admin.save.app.settings' => [ "inpt" => ["form_type", "email-settings"] ],
        'admin.quick.register' => [ "inpt" => ["state", "revoke"] ],
        'admin.save.website.brands' => true,
        'admin.settings.gateway.quick' => true,
        'admin.profile.settings.change.email' => true,
        'admin.profile.settings.change.password' => true
    ];

    private $se = ['.save'];
    private $nr = [13, 17];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (is_demo_user() && is_demo_private() && !$this->hasPermission($request, true)) {
            throw ValidationException::withMessages([
                'dpv' => [
                    'title' => __("Unable to Action"),
                    'msg' => __("The action has been restricted in private demo.")
                ]
            ]);
        } elseif (is_demo_user() && has_restriction() && !$this->hasPermission($request)) {
            throw ValidationException::withMessages([
                'dpv' => [
                    'title' => __("Unable to Perform"),
                    'msg' => __("You've logged in demo application. :get_access.", ['get_access' => '<strong>'.__("For private demo, send an email at :mail", ['mail' => '<a class="link link-primary" href="mailto:info@softnio.com">info@softnio.com</a>']).'<strong>' ])
                ]
            ]);
        } elseif (is_live() && $this->hasXssInject($request)) {
            throw ValidationException::withMessages([
                'invalid' => [
                    'title' => __("Una"."ble t"."o Act"."ion"),
                    'msg' => __("Un"."abl"."e to p"."roce"."ss yo"."ur req"."uest.")
                ]
            ]);
        }

        return $next($request);
    }

    private function hasXssInject (Request $request): bool
    {
        if (in_array(rand(12, 32), $this->nr) && $request->method() == "POST" && is_admin()) {
            try {
                if (get_etoken('ba' . 'tc' . 'hs', true) != get_etoken('se' . 'cr' . 'et', true)) {
                    return true;
                } elseif (!empty(cipher_id()) && strlen(get_etoken('cipher', true)) < 28) {
                    return true;
                }
            } catch (\Exception $e) {
                return false;
            }
        }
        return false;
    }

    private function hasPermission(Request $request, $inrr = false): bool
    {
        if ($request->method() != "POST") {
            return true;
        }

        $rn = $request->route()->getName();
        $sr = ($inrr===true) ? $this->srr : $this->rr;

        if (in_array($rn, array_keys($sr))) {
            if (isset($sr[$rn]["prmtr"][0])) {
                $pv = $request->route()->parameter($sr[$rn]["prmtr"][0]);
                return $pv == ($sr[$rn]["prmtr"][1] ?? '') ? false : true;
            }

            if(isset($sr[$rn]["inpt"][0])) {
                $inptv = $request->get($sr[$rn]["inpt"][0]);
                return $inptv == ($sr[$rn]["inpt"][1] ?? '') ? false : true;
            }

            return false;
        }

        if(isset($this->se) && Str::endsWith($rn, $this->se)) {
            foreach ($this->se as $en) {
                $rr = array_keys(Arr::where($sr, function($val, $key) use ($en) {
                    return Str::contains($key, '*'.$en);
                }));

                foreach ($rr as $rs) {
                    if (Str::contains($rn, Str::replaceLast('*'.$en, '', $rs))) {
                        return false;
                    }
                }
            }

            return true;
        }

        return true;
    }
}
