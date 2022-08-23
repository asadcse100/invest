<?php

namespace NioModules\WdPaypal\Controllers;

use NioModules\WdPaypal\WdPaypalModule;
use App\Models\UserAccount;
use App\Models\WithdrawMethod;

use App\Helpers\NioHash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserAccountController extends Controller
{
    private function getWithdrawMethod()
    {
        return WithdrawMethod::where('slug', WdPaypalModule::SLUG)->first();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function form(Request $request)
    {
        if ($request->ajax()) {
            $actionUrl = route('user.withdraw.account.'.WdPaypalModule::SLUG.'.save');
            $quickAdd = $request->get('quick_added' , false);

            $method = $this->getWithdrawMethod();
            $currencies = $method->currencies;
            $fallback = isset($method->currencies[0]) ? $method->currencies[0] : '';
            $default = data_get($method->config, 'meta.currency', $fallback);
            return view('WdPaypal::account-form', compact('actionUrl', 'currencies', 'default', 'quickAdd'));
        }
    }

    private function validateInput(Request $request)
    {
        return $request->validate([
            'wdm-label' => 'nullable|string',
            'wdm-email' => 'required|email',
            'wdm-currency' => 'nullable'
        ], [
            'wdm-email.*' => __('Enter a valid email address.')
        ]);
    }

    /**
     * @param Request $request
     * @version 1.0.0
     * @since 1.0
     */
    public function save(Request $request)
    {
        $input = $this->validateInput($request);

        $name = ($input['wdm-label']) ? strip_tags($input['wdm-label']) : 'AC-'.substr( sprintf('%04s', auth()->user()->id), -4, 4).'-'.rand(1001, 9999);
        $config = [ 'email' => $input['wdm-email'], 'currency' => $input['wdm-currency'] ];

        $account = new UserAccount();
        $account->fill([
            'user_id' => auth()->user()->id,
            'slug' => WdPaypalModule::SLUG,
            'name' => $name,
            'config' => $config
        ]);
        $account->save();

        if ($request->get('quick_added') == 'yes') {
            return redirect()->route('withdraw.redirect.amount');
        }

        return response()->json(["msg" => __('Your PayPal account successfully added.'), "msg_title" => __('Account Added')]);
    }

    public function edit($hash)
    {
        $id = NioHash::toID($hash);
        $method = $this->getWithdrawMethod();
        $currencies = $method->currencies;
        $fallback = isset($method->currencies[0]) ? $method->currencies[0] : '';
        $default = data_get($method->config, 'meta.currency', $fallback);

        $userAccount = UserAccount::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->first();

        if (blank($userAccount)) {
            throw ValidationException::withMessages([ 'acc' => [0 => __('Invalid Account'), 1 => __('Sorry, account may invalid or not found.')] ]);
        }

        $actionUrl = route('user.withdraw.account.'.WdPaypalModule::SLUG.'.update', ['id' => NioHash::of($id)]);
        $quickAdd = false;

        return view('WdPaypal::account-form', compact('userAccount', 'currencies', 'default', 'actionUrl', 'quickAdd'));
    }

    public function update($hash, Request $request)
    {
        $id = NioHash::toID($hash);
        $input = $this->validateInput($request);

        $name   = strip_tags($input['wdm-label']);
        $config = [ 'email' => $input['wdm-email'], 'currency' => $input['wdm-currency'] ];

        $account = UserAccount::where('id', $id)->where('slug', WdPaypalModule::SLUG)
            ->where('user_id', auth()->user()->id)->first();

        if (blank($account)) {
            throw ValidationException::withMessages([ 'acc' => [0 => __('Invalid Account'), 1 => __('Sorry, account may invalid or not found.')] ]);
        }

        $account->update([
            'user_id' => auth()->user()->id,
            'slug' => WdPaypalModule::SLUG,
            'name' => $name,
            'config' => $config
        ]);

        return response()->json(["reload" => 1500, "msg" => __('The PayPal account successfully updated.'), "msg_title" => __('Account Updated')]);
    }

    public function delete($hash)
    {
        $id = NioHash::toID($hash);
        $account = UserAccount::where('id', $id)->where('slug', WdPaypalModule::SLUG)->where('user_id', auth()->user()->id)->first();

        if (blank($account)) {
            throw ValidationException::withMessages([ 'acc' => [0 => __('Invalid Account'), 1 => __('Sorry, account may invalid or not found.')] ]);
        }

        $account->delete();

        return response()->json(['msg' => __('The account successfully deleted.'), "msg_title" => __('Account Deleted')]);
    }

}
