<?php

namespace App\Http\Controllers\Invest\Admin;

use App\Enums\Boolean;
use App\Enums\InvestmentStatus;
use App\Enums\SchemeStatus;

use App\Models\IvScheme;
use App\Models\IvSchemeMeta;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Controller;
use App\Services\InvestormService;

class InvestmentSchemeController extends Controller
{
    private $investment;

    public function __construct(InvestormService $investment)
    {
        $this->investment = $investment;
    }

    public function schemeList(Request $request, $status = null)
    {
        if (!Schema::hasColumn('iv_schemes', 'deleted_at')) {
            return back()->with(['notice' => __("To continue, please install the update and migrate the application database.")]);
        }

        $schemes = IvScheme::query();

        if ($status) {
            $schemes->withoutGlobalScope('exceptArchived')->where('status', $status);
        } else {
            $schemes->withoutGlobalScope('exceptArchived')->where('status', SchemeStatus::ACTIVE);
        }

        $schemes = $schemes->orderBy('id', 'asc')->get();
        if ($request->isXmlHttpRequest()) {
            return view("investment.admin.schemes.cards", compact('schemes', 'status'))->render();
        }

        return view("investment.admin.schemes.list", compact('schemes', 'status'));
    }

    public function actionScheme(Request $request, $action=null)
    {
        $type = (!empty($action)) ? $action : $request->get('view');

        if(!in_array($type, ['new', 'edit'])) {
            throw ValidationException::withMessages(['action' => __("Sorry, we are unable to proceed your action.")]);
        }

        $scheme = compact([]);
        $schmeID = ($request->get('uid')) ? $request->get('uid') : $request->get('id');

        if ($schmeID) {
            $scheme = IvScheme::find(get_hash($schmeID));
            if (blank($scheme)) {
                throw ValidationException::withMessages(['id' => __("The scheme id invalid or may not avialble.")]);
            }
        }

        return view("investment.admin.schemes.form", compact('scheme', 'type'));
    }

    public function updateSchemeStatus(Request $request)
    {
        $input = $request->validate([
            'uid' => 'required',
            'action' => 'required',
        ]);

        $ivScheme = IvScheme::withoutGlobalScope('exceptArchived')->find(get_hash(Arr::get($input, 'uid')));

        if (blank($ivScheme)) {
            throw ValidationException::withMessages(["id" => __("The investment scheme id is invalid or not found.")]);
        }

        $allowedStatuses = IvScheme::NEXT_STATUSES[$ivScheme->status] ?? [];

        $status = Arr::get($input, 'action');
        if (!in_array($status, $allowedStatuses)) {
            throw ValidationException::withMessages(['status' => __("Scheme status cannot be updated to :state", ["state" => $status]) ]);
        }

        $ivlistStatus = session()->get('ivlistStatus');
        $hasAnyPlans  = $ivScheme->plans()->count() > 0 ? true : false;
        $hasRunning   = $ivScheme->plans()->whereIn('status', [InvestmentStatus::ACTIVE, InvestmentStatus::PENDING])->count() > 0 ? true : false;

        if ($request->action === 'delete') {
            if ($hasRunning) {
                throw ValidationException::withMessages(['status' => __("Sorry, unable to delete the scheme due to active or pending invested plan.")]);
            }

            if ($hasAnyPlans) {
                $ivScheme->delete();
            } else {
                $ivScheme->forceDelete();
            }
        } else {
            $ivScheme->status = $status;
            $ivScheme->save();
        }

        return response()->json([
            'type' => 'success',
            'title' => __("Status Updated"), 
            'msg' => __('The investment scheme (:name) status updated to :state', ['name' => $ivScheme->name, "state" => $status]),
            'embed' => $this->schemeList($request, $ivlistStatus)
        ]);
    }

    public function updateScheme(Request $request, $id=null)
    {
        $schemeID = (!empty($id)) ? get_hash($id) : get_hash($request->get('uid'));

        if($schemeID != $request->get('id')) {
            throw ValidationException::withMessages([ 'invalid' => __('The investment scheme id is invalid or not found.') ]);
        }

        $scheme = IvScheme::find($schemeID);

        if (blank($scheme)) {
            throw ValidationException::withMessages(['failed' => __('Unable to find the scheme, please try again.')]);
        }

        $slug = $scheme->slug ??  '';
        $minNum = is_crypto(base_currency()) ? 'min:0.000001' : 'min:0.01';
        $minDP = is_crypto(base_currency()) ? '6' : '2';

        if ($scheme->is_restricted) {
            $rules = [
                'desc' => 'nullable|string',
                'plan_limit' => 'required|integer|min:0',
                'plan_limit_user' => 'required|integer|min:0',
            ];

            if ($scheme->is_fixed === Boolean::NO) {
                $rules = Arr::add($rules, 'amount', 'bail|required|numeric|'.$minNum.'|regex:/^\d+(\.\d{1,'.$minDP.'})?$/');
                $rules = Arr::add($rules, 'maximum', 'bail|nullable|numeric|exclude_if:maximum,0|gt:amount|'.$minNum.'|regex:/^\d+(\.\d{1,'.$minDP.'})?$/');
            }

            $request->validate($rules, [
                "amount.numeric" => __("The investment amount should be valid number."),
                "maximum.numeric" => __("The maximum amount should be valid number."),
            ], [
                "amount.regex" => __('Allow only :num digit after decimal point in :label.', ['label' => __("Amount"), 'num' => $minDP]),
                "maximum.regex" => __('Allow only :num digit after decimal point in :label.', ['label' => __("Maximum"), 'num' => $minDP]),
                "plan_limit.min" => __(":Label must be at least :num.", ['label' => __('Plan restriction limit'), 'num' => 0]),
                "plan_limit_user.min" => __(":Label must be at least :num.", ['label' => __('User restriction limit'), 'num' => 0]),
            ]);
        } else {
            $request->validate([
                "name" => 'required|string',
                "short" => 'required|string',
                "desc" => 'nullable|string',
                "amount" => 'bail|required|numeric|'.$minNum.'|regex:/^\d+(\.\d{1,'.$minDP.'})?$/',
                "maximum" => 'bail|nullable|numeric|exclude_if:maximum,0|gt:amount|'.$minNum.'|regex:/^\d+(\.\d{1,'.$minDP.'})?$/',
                "term" => 'required|integer|not_in:0',
                "rate" => 'required|numeric|not_in:0',
                "duration" => 'required|string',
                "types" => 'required|string',
                "period" => 'required|string',
                "payout" => "required|string",
                "plan_limit" => 'required|integer|min:0',
                "plan_limit_user" => 'required|integer|min:0'
            ], [
                "amount.numeric" => __("The investment amount should be valid number."),
                "maximum.numeric" => __("The maximum amount should be valid number."),
                "amount.regex" => __('Allow only :num digit after decimal point in :label.', ['label' => __("Amount"), 'num' => $minDP]),
                "maximum.regex" => __('Allow only :num digit after decimal point in :label.', ['label' => __("Maximum"), 'num' => $minDP]),
                "rate.numeric" => __("Enter a valid amount of interest rate."),
                "term.integer" => __("Term duration should be valid number."),
                "term.not_in" => __("Term duration should not be zero."),
                "rate.not_in" => __("Interest rate should not be zero."),
                "plan_limit.min" => __(":Label must be at least 0.", ['label' => __('Plan restriction limit')]),
                "plan_limit_user.min" => __(":Label must be at least 0.", ['label' => __('User restriction limit')]),
            ]);
        }

        if($this->existNameSlug($request->get('name'), $slug)==true) {
            throw ValidationException::withMessages([ 'name' => __('The investment scheme (:name) already exist. Please try with different name.', ['name' => $request->get('name')]) ]);
        }

        if( !($request->get('fixed')) && $request->get('maximum') > 0 && $request->get('amount') >= $request->get('maximum') ) {
            throw ValidationException::withMessages(['maximum' => __('The maximum amount should be zero or more than minimum amount of investment.')]);
        }

        if ($request->has('period') && !array_key_exists($request->get("period"), InvestormService::TERM_CONVERSION[$request->get('duration')])) {
            throw ValidationException::withMessages(['period' => __('Interest period is not valid for term duration.')]);
        }

        $data = [
            "name" => strip_tags($request->get("name")),
            "slug" => Str::slug(strip_tags($request->get("name"))),
            "short" => strip_tags($request->get('short')),
            "desc" => strip_tags($request->get('desc')),
            "amount" => $request->get('amount'),
            "maximum" => $request->get('maximum'),
            "is_fixed" => $request->get('fixed') ? Boolean::YES : Boolean::NO,
            "term" => $request->get("term"),
            "term_type" => $request->get("duration"),
            "rate" => $request->get("rate"),
            "rate_type" => $request->get("types"),
            "calc_period" => $request->get("period"),
            "days_only" => $request->get("daysonly") ? Boolean::YES : Boolean::NO,
            "capital" => $request->get("capital") ? Boolean::YES : Boolean::NO,
            "payout" => $request->get("payout"),
            "featured" => $request->get('featured') ? Boolean::YES : Boolean::NO,
            "status" => $request->get('status') ? SchemeStatus::ACTIVE : SchemeStatus::INACTIVE
        ];

        if ($scheme->is_restricted) {
            $required = ['desc', 'featured', 'status'];
            if ($scheme->is_fixed === Boolean::NO) $required = array_merge($required, ['amount', 'maximum']);
            $data = Arr::only($data, $required);
        }

        DB::beginTransaction();

        try {
            $scheme->fill($data);
            $scheme->save();

            foreach ($request->only(['plan_limit', 'plan_limit_user']) as $key => $value) {
                IvSchemeMeta::updateOrCreate(['scheme_id' => $scheme->id, 'key' => $key], ['value' => $value]);
            }

            DB::commit();
            return response()->json([
                'msg' => __('The investment scheme has been updated.'), 
                'title' => __("Scheme Updated"), 'modal' => 'hide',
                'embed' => $this->schemeList($request, $request->route('status')),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['failed' => __('Unable to update investment scheme, please try again.')]);
        }
    }

    private function existNameSlug($name, $old=null) {
        $slug = Str::slug($name);
        $scheme = IvScheme::where('slug', $slug)->first();

        if ($slug == $old || blank($scheme)) return false;

        return true;
    }
}
