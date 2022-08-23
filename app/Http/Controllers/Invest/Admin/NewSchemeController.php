<?php

namespace App\Http\Controllers\Invest\Admin;

use App\Enums\Boolean;
use App\Enums\SchemeStatus;

use App\Models\IvScheme;
use App\Models\IvSchemeMeta;
use App\Services\InvestormService;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;

class NewSchemeController extends Controller
{

    public function saveScheme(Request $request)
    {
        $minNum = is_crypto(base_currency()) ? 'min:0.000001' : 'min:0.01';
        $minDP = is_crypto(base_currency()) ? '6' : '2';

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
            "plan_limit_user" => 'required|integer|min:0',
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

        if($this->existNameSlug($request->get('name'))==true) {
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

        DB::beginTransaction();

        try {
            $ivScheme = new IvScheme();
            $ivScheme->fill($data);
            $ivScheme->save();

            $getScheme = IvScheme::where('slug', data_get($data, 'slug'))->first();
            if (!blank($getScheme)) {
                foreach ($request->only(['plan_limit', 'plan_limit_user']) as $key => $value) {
                    IvSchemeMeta::updateOrCreate(['scheme_id' => $getScheme->id, 'key' => $key], ['value' => $value]);
                }
                DB::commit();
                return response()->json([ 'title' => __("Scheme Added"), 'msg' => __('The new investment scheme has been added.'), 'reload' => true ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['failed' => __('Unable to add new investment scheme, please try again.')]);
        }
    }

    private function existNameSlug($name, $old=null) {
        $slug = Str::slug($name);
        $scheme = IvScheme::where('slug', $slug)->first();

        if ($slug == $old || blank($scheme)) return false;

        return true;
    }
}
