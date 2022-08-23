<?php

namespace App\Http\Controllers;

use App\Enums\UserRoles;
use App\Enums\SchemeStatus;

use App\Models\User;
use App\Models\IvScheme;
use App\Services\Shortcut;
use App\Services\MaintenanceService as MService;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class PublicController extends Controller
{
	public function __construct()
    {

    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function welcome()
    {
    	if (empty(gss('system_service'))) {
	    	$MService = new MService();
	    	$installer = $MService->getInstaller();

	    	if (!empty($installer)) {
	    		return redirect()->route($installer);
	    	}
    	}

        if (gss('front_page_enable', 'yes')=='no') {
            return redirect()->route('auth.login.form');
        }

        $logged     = Auth::check();
        $admins     = ($logged && in_array(Auth::user()->role, [UserRoles::ADMIN, UserRoles::SUPER_ADMIN]));

        $plan_x0 = gss('top_iv_plan_x0');
        $plan_x1 = gss('top_iv_plan_x1');
        $plan_x2 = gss('top_iv_plan_x2');

        $schemes  = [];
        $plan1    = IvScheme::find($plan_x0);
        $plan2    = IvScheme::find($plan_x1);
        $plan3    = IvScheme::find($plan_x2);

        if (!empty($plan1) && !empty($plan2) && !empty($plan3)) {
            $schemes['highlight'] = $plan1;
            $schemes['one'] = $plan2;
            $schemes['two'] = $plan3;
        }


        return view('frontend.index', compact('schemes', 'admins'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function language(Request $request)
    {
        $lang = $request->get('lang', gss('language_default_public', 'en'));

        $setlocale = Cookie::queue(Cookie::make('app_language', $lang, (60 * 24 * 365)));

        return back();
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function investments()
    {
        if (gss('invest_page_enable', 'yes')=='yes' && Auth::check() && Auth::user()->role==UserRoles::USER) {
            return redirect()->route('user.investment.plans');
        }
        if (gss('invest_page_enable', 'yes')=='no') {
            return (Auth::check()) ? redirect()->route('user.investment.plans') : redirect()->route('auth.login.form');
        }

        $logged      = Auth::check();
        $admins      = ($logged && in_array(Auth::user()->role, [UserRoles::ADMIN, UserRoles::SUPER_ADMIN]));

        $schemeQuery = IvScheme::query();
        $orderby     = sys_settings('iv_plan_order');

        switch ($orderby) {
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
        if (sys_settings('iv_show_plans', 'default') == 'featured') {
            $schemeQuery->where('featured', 1);
        }

        $schemes = $schemeQuery->where('status', SchemeStatus::ACTIVE)->get();

        return view('frontend.investments', compact('schemes', 'admins'));
    }

    public function gdprCookie(Request $request)
    {
        try {
            $request->validate(['consent' => 'required|string|in:yes,no']);
            $name = '_pconsent_' . ghp();

            if ($request->consent == 'yes') {
                $minutes = 2 * 365 * 24 * 60;
                $cookie = cookie($name, 'yes', $minutes);
                return response()->json(['status' => 'success', 'message' => __("Cookie Accepted.")])->withCookie($cookie);
            }

            if ($request->consent == 'no') {
                $minutes = 30 * 24 * 60;
                $cookie = cookie($name, 'no', $minutes);
                return response()->json(['status' => 'success', 'message' => __("Cookie Denied.")])->withCookie($cookie);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => __("Please reload the page and try again.")]);
        }
    }
}
