<?php

namespace NioModules\WdBank\Controllers;

use NioModules\WdBank\WdBankModule;
use App\Models\UserAccount;
use App\Models\WithdrawMethod;

use Validator;
use App\Helpers\NioHash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserAccountController extends Controller
{
    private function getWithdrawMethod()
    {
        return WithdrawMethod::where('slug', WdBankModule::SLUG)->first();
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
            $action = route('user.withdraw.account.'.WdBankModule::SLUG.'.save');
            $quickAdd = $request->get('quick_added' , false);
            $method = $this->getWithdrawMethod();
            $countries = filtered_countries($method);
            $currencies = $method->currencies;
            $fallback = isset($method->currencies[0]) ? $method->currencies[0] : '';
            $default = data_get($method->config, 'meta.currency', $fallback);

            return view('WdBank::account-form', compact( 'action', 'method', 'currencies', 'default', 'countries', 'quickAdd'));
        }
    }

    private function isRequired($config, $field) 
    {
        return (data_get($config, $field.'.req') == 'yes' && data_get($config, $field.'.show') == 'yes') ? true : false;
    }


    private function getFormData($request)
    {
        $formConfig = data_get($this->getWithdrawMethod(), 'config.form');
        $data = [];
        
        $validate = Validator::make($request->all(), [
            "acc-type" => ['required'],
            "acc-name" => ($this->isRequired($formConfig, 'acc_name')) ? ['required'] : ['nullable'],
            "acc-number" => ['required'],
            "country" => ($this->isRequired($formConfig, 'country')) ? ['required'] : ['nullable'],
            "currency" => ($this->isRequired($formConfig, 'currency')) ? ['required'] : ['nullable'],
            "bank-name" => ['required'],
            "bank-branch" => ($this->isRequired($formConfig, 'bank_branch')) ? ['required'] : ['nullable'],
            "bank-address" => ($this->isRequired($formConfig, 'bank_address')) ? ['required'] : ['nullable'],
            "sortcode" => ($this->isRequired($formConfig, 'sortcode')) ? ['required'] : ['nullable'],
            "routing" => ($this->isRequired($formConfig, 'routing')) ? ['required'] : ['nullable'],
            "swift" => ($this->isRequired($formConfig, 'swift')) ? ['required'] : ['nullable'],
            "iban" => ($this->isRequired($formConfig, 'iban')) ? ['required'] : ['nullable'],
            "wdm-label" => ['nullable', 'string'],
        ], [
            "acc-type.required" => __('Choose "Account Type" of your bank account.'),
            "country.required" => __('Please select your "Bank Location" (country).'),
            "currency.required" => __('Select "Bank Currency" of your bank account.'),
            "acc-name.required" => __('Enter the "Account Name" of your bank account and it should match with account.'),
            "acc-number.required" => __('Enter the "Account Number" of your bank account and make sure it is correct.'),
            "bank-name.required" => __('Enter the "Bank Name" of your account.'),
            "bank-branch.required" => __('Enter the "Branch Name" of your bank.'),
            "bank-address.required" => __('Enter the "Bank Adddress" of your bank.'),
            "sortcode.required" => __('Enter the "Sort Code" of your bank.'),
            "routing.required" => __('Enter the "Routing Number" of your bank.'),
            "swift.required" => __('Enter the "Swift / BIC" code of your bank.'),
            "iban.required" => __('Enter the "IBAN Number" of your bank.')
        ]);

        if ($validate->fails()) {
            $allError = $validate->errors()->toArray();
            $errorChunk = array_chunk($allError, 1, true);
            
            $error = [
                'type' => 'warning', 
                'message' => __('The given data was invalid.'),
                'errors' => (isset($errorChunk[0])) ? $errorChunk[0] : $validate->errors()->first(),
            ];
            return $data = (object) [ 'status' => false, 'data' => $error ];
        }

        $number = ($request->get('acc-number')) ? strip_tags($request->get('acc-number')) : rand(1001, 9999);
        $formData = [
            'name' => ($request->get('wdm-label')) ? strip_tags($request->get('wdm-label')) : 'AC-'.substr( sprintf('%04s', auth()->user()->id), -4, 4).str_end($number, true),
            'config' => [
                'acc_type' => ($request->get('acc-type')) ? strip_tags($request->get('acc-type')) : '',
                'country' => ($request->get('country')) ? strip_tags($request->get('country')) : '',
                'currency' => ($request->get('currency')) ? strip_tags($request->get('currency')) : '',
                'acc_name' => ($request->get('acc-name')) ? strip_tags($request->get('acc-name')) : '',
                'acc_no' => ($request->get('acc-number')) ? strip_tags($request->get('acc-number')) : '',
                'bank_name' => ($request->get('bank-name')) ? strip_tags($request->get('bank-name')) : '',
                'bank_branch' => ($request->get('bank-branch')) ? strip_tags($request->get('bank-branch')) : '',
                'bank_address' => ($request->get('bank-address')) ? strip_tags($request->get('bank-address')) : '',
                'sortcode' => ($request->get('sortcode')) ? strip_tags($request->get('sortcode')) : '',
                'routing' => ($request->get('routing')) ? strip_tags($request->get('routing')) : '',
                'swift' => ($request->get('swift')) ? strip_tags($request->get('swift')) : '',
                'iban' => ($request->get('iban')) ? strip_tags($request->get('iban')) : '',
            ]
        ];

        return $data = (object) [ 'status' => true, 'data' => $formData ];
    }

    public function save(Request $request)
    {
        $input = $this->getFormData($request);
        if($input->status===true) {
            $account = new UserAccount();
            $account->fill([
                'user_id' => auth()->user()->id,
                'slug' => WdBankModule::SLUG,
                'name' => $input->data['name'],
                'config' => $input->data['config']
            ]);
            $account->save();

            if ($request->get('quick_added') == 'yes') {
                return redirect()->route('withdraw.redirect.amount');
            }
            return response()->json(["msg" => __('Your bank account successfully added.'), "msg_title" => __('Account Added')]);
        } else {
            if (is_array($input->data['errors'])) {
                throw ValidationException::withMessages($input->data['errors']);
            }
            return response()->json(["type"=> $input->data['type'], "message"=> $input->data['message'], "msg" => $input->data['errors'] ]);
        }

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

        $countries = filtered_countries($method);
        $action = route('user.withdraw.account.'.WdBankModule::SLUG.'.update', ['id' => NioHash::of($id)]);
        $quickAdd = false;

        return view('WdBank::account-form', compact('userAccount', 'method', 'action', 'currencies', 'default', 'countries', 'quickAdd'));
    }

    public function update($hash, Request $request)
    {
        $id = NioHash::toID($hash);
        $input = $this->getFormData($request);
        if($input->status===true) {
            $account = UserAccount::where('id', $id)->where('slug', WdBankModule::SLUG)
                ->where('user_id', auth()->user()->id)->first();

            if (blank($account)) {
                throw ValidationException::withMessages([ 'acc' => [0 => __('Invalid Account'), 1 => __('Sorry, account may invalid or not found.')] ]);
            }

            $account->update([
                'user_id' => auth()->user()->id,
                'slug' => WdBankModule::SLUG,
                'name' => $input->data['name'],
                'config' => $input->data['config']
            ]);

            return response()->json(["msg" => __('The bank account successfully updated.'), "msg_title" => __('Account Updated')]);
        } else {
            if (is_array($input->data['errors'])) {
                throw ValidationException::withMessages($input->data['errors']);
            }
            return response()->json(["type"=> $input->data['type'], "message"=> $input->data['message'], "msg" => $input->data['errors'] ]);
        }
    }

    public function delete($hash)
    {
        $id = NioHash::toID($hash);
        $account = UserAccount::where('id', $id)->where('slug', WdBankModule::SLUG)->where('user_id', auth()->user()->id)->first();

        if (blank($account)) {
            throw ValidationException::withMessages([ 'acc' => [0 => __('Invalid Account'), 1 => __('Sorry, account may invalid or not found.')] ]);
        }

        $account->delete();

        return response()->json(['msg' => __('The account successfully deleted.'), "msg_title" => __('Account Deleted')]);
    }
}
