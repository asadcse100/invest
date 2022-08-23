<?php
namespace App\Helpers;

use App\Enums\TransactionStatus;

class MsgState
{
	public function __construct()
    {

    }

	// Get message by name/keyword
    public function get($name, $prefix=null) {
    	if(empty($name)) return false;

    	$msg = self::list();
    	$key = ($prefix) ? $prefix.'-'.$name : $name;

    	return (isset($msg[$key])) ? $msg[$key] : false;
    }

    private static function hasCode(){
    	return sys_settings('error_code_display', true);
    }

    // Get message by name/keyword
    public static function of($name, $prefix=null) {
    	if(empty($name)) return false;

    	$msg = self::list();
    	$key = ($prefix) ? $prefix.'-'.$name : $name;

    	return (isset($msg[$key])) ? $msg[$key] : false;
    }

    // List of help/support context
    public static function helps($name=null, $def='default') {
    	$context['default'] = __('Please feel free to contact if you need any further information.');

    	$context['simple-problem'] = __('Please feel free to contact us if you face any problem.');
    	$context['simple-ask'] = __('Please feel free to contact us if you have any question.');

    	$context['support'] = __('If you continue to having trouble? :Contact or email at :email', ['contact' => get_page_link('contact', __('Contact us')), 'email' => get_mail_link()]);

    	$default = ($def && isset($context[$def])) ? $context[$def] : '';

    	return ($name && isset($context[$name])) ? $context[$name] : $default;
    }

    // List of messages
	public static function list() {
        $messages['default'] = [
        	'icon' => 'ni-alert bg-warning',
            'title' =>  'Message title here.',
            'notice' => [
            	'caption' => 'Content of message.',
            	'note'	=> '',
            	'class'	=> ''
            ],
            'button' => [
            	'text'	=> __('Go to Dashboard'),
            	'url'	=> route('dashboard'),
            	'class'	=> 'btn-primary'
            ],
            'link' => [
            	'text'	=> 'Button text',
            	'url'	=> '#',
            	'class'	=> 'link-primary'
            ],
            'help'	=> self::helps()
        ];


        ////////////////////////////////////
        // Deposit related
        ////////////////////////////////////
        $messages['deposit-disable'] = [
        	'icon' => 'ni-alert bg-warning',
            'title' =>  sys_settings('deposit_disable_title', __('Oops, deposit temporarily disabled!')),
            'notice' => [
            	'caption' => sys_settings('deposit_disable_notice'),
            	'note'	=> '',
            	'class'	=> ''
            ],
            'button' => [
            	'text'	=> __('Go to Dashboard'),
            	'url'	=> route('dashboard'),
            	'class'	=> 'btn-primary'
            ],
            'link' =>  false,
            'help'	=> self::helps('simple-ask')
        ];

        $messages['deposit-limit'] = [
        	'icon' => 'ni-info bg-warning',
            'title' =>  __('Reached maximum limit!'),
            'notice' => [
            	'caption' => __("Sorry, you have reached maximum number of pending request! You can deposit again, once your ongoing request are completed."),
            	'note'	=> (self::hasCode()) ? __('Error Reference ID :code', ['code' => '0x0210']) : '',
            	'class'	=> 'sm'
            ],
            'button' => [
            	'text'	=> __('Go to Dashboard'),
            	'url'	=> route('dashboard'),
            	'class'	=> 'btn-primary'
            ],
            'link' =>  [
            	'text'	=> __('View pending transactions'),
            	'url'	=> route('transaction.list', ['list_scheduled' => TransactionStatus::PENDING]),
            	'class'	=> ''
            ],
            'help'	=> self::helps('simple-ask')
        ];

        $messages['deposit-no-method'] = [
        	'icon' => 'ni-alert bg-danger',
            'title' => __('Unavailable deposit service!'),
            'notice' => [
            	'caption' => __("We regret that there is no deposit option available at the moment. Please try again later or contact us."),
            	'note'	=> (self::hasCode()) ? __('Error Reference ID :code', ['code' => '0x0211']) : '',
            	'class'	=> ''
            ],
            'button' => [
            	'text'	=> __('Go to Dashboard'),
            	'url'	=> route('dashboard'),
            	'class'	=> 'btn-primary'
            ],
            'link' =>  false,
            'help'	=> self::helps('support')
        ];

        $messages['deposit-invalid-method'] = [
        	'icon' => 'ni-alert bg-warning',
            'title' => __('Oops, temporarily unavailable!'),
            'notice' => [
            	'caption' => __("Sorry, we are unable to process with this payment method at this time. Please try a different payment method."),
            	'note'	=> (self::hasCode()) ? __('Error Reference ID :code', ['code' => '0x0212']) : '',
            	'class'	=> ''
            ],
            'button' => [
            	'text'	=> __('Try another method'),
            	'url'	=> route('deposit'),
            	'class'	=> 'btn-primary'
            ],
            'link' =>  [
            	'text'	=> __('Go to Dashboard'),
            	'url'	=> route('dashboard'),
            	'class'	=> 'link-primary'
            ],
            'help'	=> self::helps('support')
        ];

        $messages['deposit-try-method'] = [
        	'icon' => 'ni-alert bg-warning',
            'title' => __('Sorry, no payment option!'),
            'notice' => [
            	'caption' => __("We regret that there is no payment option available for the selected currency. Choose another currency & try again."),
            	'note'	=> (self::hasCode()) ? __('Error Reference ID :code', ['code' => '0x0213']) : '',
            	'class'	=> ''
            ],
            'button' => [
            	'text'	=> __('Try another method'),
            	'url'	=> route('deposit'),
            	'class'	=> 'btn-primary'
            ],
            'link' =>  [
            	'text'	=> __('Go to Dashboard'),
            	'url'	=> route('dashboard'),
            	'class'	=> 'link-primary'
            ],
            'help'	=> self::helps('support')
        ];

        $messages['deposit-wrong'] = [
            'icon' => 'ni-alert bg-danger',
            'title' => __('Sorry, unable to proceed!'),
            'notice' => [
                'caption' => __("We are temporarily unable to process your deposit request. Please try again later."),
                'note'  => (self::hasCode()) ? __('Error Reference ID :code', ['code' => '0x0214']) : '',
                'class' => 'sm'
            ],
            'button' => [
                'text'  => __('Try Again'),
                'url'   => route('deposit'),
                'class' => 'btn-primary'
            ],
            'link' =>  [
                'text'  => __('Go to Dashboard'),
                'url'   => route('dashboard'),
                'class' => 'link-primary'
            ],
            'help'  => self::helps('support')
        ];

        $messages['deposit-cancel-timeout'] = [
            'icon' => 'ni-wallet-out bg-warning',
            'title' =>  "Oops, deposit cannot be cancelled!",
            'notice' => [
                'caption' => __("Sorry, your deposit transaction can not be cancelled. Please contact administration."),
                'note' => (self::hasCode()) ? __('Error Reference ID :code', ['code' => '0x0215']) : '',
                'class' => ''
            ],
            'button' => [
                'text'  => __('Go to Dashboard'),
                'url'   => route('dashboard'),
                'class' => 'btn-primary'
            ],
            'help'  => self::helps('support')
        ];

        $messages['deposit-no-rate'] = [
            'icon' => 'ni-alert bg-danger',
            'title' => __('Sorry, unable to proceed!'),
            'notice' => [
                'caption' => __("We are temporarily unable to process your deposit request. Please try again later."),
                'note'  => (self::hasCode()) ? __('Error Reference ID :code', ['code' => '0x0216']) : '',
                'class' => 'sm'
            ],
            'button' => [
                'text'  => __('Try Again'),
                'url'   => route('deposit'),
                'class' => 'btn-primary'
            ],
            'link' =>  [
                'text'  => __('Go to Dashboard'),
                'url'   => route('dashboard'),
                'class' => 'link-primary'
            ],
            'help'  => self::helps('support')
        ];

        $messages['deposit-invalid-action'] = [
            'icon' => 'ni-alert bg-warning',
            'title' => __('Sorry, Unable to proceed!'),
            'notice' => [
                'caption' => __("Sorry, we are unable to proceed your request. Please reload the page and try again."),
                'note'  => (self::hasCode()) ? __('Error Reference ID :code', ['code' => '0x0217']) : '',
                'class' => 'sm'
            ],
            'button' => false,
            'link' =>  [
                'text'  => __('Go to Dashboard'),
                'url'   => route('dashboard'),
                'class' => 'link-primary'
            ],
            'help'  => self::helps('support')
        ];

        $messages['deposit-amount'] = [
            'icon' => 'ni-alert bg-danger',
            'title' => __('Sorry, unable to proceed!'),
            'notice' => [
                'caption' => __("We are temporarily unable to process your deposit request. Please try again later."),
                'note'  => (self::hasCode()) ? __('Error Reference ID :code', ['code' => '0x0218']) : '',
                'class' => 'sm'
            ],
            'button' => [
                'text'  => __('Try Again'),
                'url'   => route('deposit'),
                'class' => 'btn-primary'
            ],
            'link' =>  [
                'text'  => __('Go to Dashboard'),
                'url'   => route('dashboard'),
                'class' => 'link-primary'
            ],
            'help'  => self::helps('support')
        ];

        ////////////////////////////////////
        // Withdraw related
        ////////////////////////////////////
        $messages['withdraw-disable'] = [
        	'icon' => 'ni-alert bg-warning',
            'title' =>  sys_settings('withdraw_disable_title', __('Oops, withdraw temporarily disabled!')),
            'notice' => [
            	'caption' => sys_settings('withdraw_disable_notice'),
            	'note'	=> '',
            	'class'	=> ''
            ],
            'button' => [
            	'text'	=> __('Go to Dashboard'),
            	'url'	=> route('dashboard'),
            	'class'	=> 'btn-primary'
            ],
            'link' =>  false,
            'help'	=> self::helps('simple-ask')
        ];

        $messages['withdraw-limit'] = [
        	'icon' => 'ni-info bg-warning',
            'title' =>  __('Reached maximum limit!'),
            'notice' => [
            	'caption' => __("Sorry, you have reached maximum number of pending request! You can withdraw again, once your ongoing request are completed."),
            	'note'	=> (self::hasCode()) ? __('Error Reference ID :code', ['code' => '0x0310']) : '',
            	'class'	=> 'sm'
            ],
            'button' => [
            	'text'	=> __('Go to Dashboard'),
            	'url'	=> route('dashboard'),
            	'class'	=> 'btn-primary'
            ],
            'link' =>  [
            	'text'	=> __('View pending transactions'),
            	'url'	=> route('transaction.list', ['list_scheduled' => TransactionStatus::PENDING]),
            	'class'	=> ''
            ],
            'help'	=> self::helps('simple-ask')
        ];

        $messages['withdraw-no-method'] = [
        	'icon' => 'ni-alert bg-danger',
            'title' => __('Unavailable withdraw service!'),
            'notice' => [
            	'caption' => __("We regret that there is no withdraw option available at the moment. Please try again later or contact us."),
            	'note'	=> (self::hasCode()) ? __('Error Reference ID :code', ['code' => '0x0311']) : '',
            	'class'	=> ''
            ],
            'button' => [
            	'text'	=> __('Go to Dashboard'),
            	'url'	=> route('dashboard'),
            	'class'	=> 'btn-primary'
            ],
            'link' =>  false,
            'help'	=> self::helps('support')
        ];

        $messages['withdraw-invalid-method'] = [
        	'icon' => 'ni-alert bg-warning',
            'title' => __('Oops, temporarily unavailable!'),
            'notice' => [
            	'caption' => __("Sorry, we are unable to process with this withdraw method at this time. Please try a different withdraw method."),
            	'note'	=> (self::hasCode()) ? __('Error Reference ID :code', ['code' => '0x0312']) : '',
            	'class'	=> ''
            ],
            'button' => [
            	'text'	=> __('Try another method'),
            	'url'	=> route('withdraw'),
            	'class'	=> 'btn-primary'
            ],
            'link' =>  [
            	'text'	=> __('Go to Dashboard'),
            	'url'	=> route('dashboard'),
            	'class'	=> 'link-primary'
            ],
            'help'	=> self::helps('support')
        ];

        $messages['withdraw-try-method'] = [
        	'icon' => 'ni-alert bg-warning',
            'title' => __('Sorry, no withdraw option!'),
            'notice' => [
            	'caption' => __("We regret that there is no withdraw option available for the selected currency. Choose another currency & try again."),
            	'note'	=> (self::hasCode()) ? __('Error Reference ID :code', ['code' => '0x0313']) : '',
            	'class'	=> ''
            ],
            'button' => [
            	'text'	=> __('Try another method'),
            	'url'	=> route('withdraw'),
            	'class'	=> 'btn-primary'
            ],
            'link' =>  [
            	'text'	=> __('Go to Dashboard'),
            	'url'	=> route('dashboard'),
            	'class'	=> 'link-primary'
            ],
            'help'	=> self::helps('support')
        ];

        $messages['withdraw-wrong'] = [
            'icon' => 'ni-alert bg-danger',
            'title' => __('Sorry, unable to proceed!'),
            'notice' => [
                'caption' => __("We are temporarily unable to process your withdraw request. Please try again later."),
                'note'  => (self::hasCode()) ? __('Error Reference ID :code', ['code' => '0x0314']) : '',
                'class' => 'sm'
            ],
            'button' => [
                'text'  => __('Try Again'),
                'url'   => route('withdraw'),
                'class' => 'btn-primary'
            ],
            'link' =>  [
                'text'  => __('Go to Dashboard'),
                'url'   => route('dashboard'),
                'class' => 'link-primary'
            ],
            'help'  => self::helps('support')
        ];

        $messages['withdraw-cancel-timeout'] = [
            'icon' => 'ni-wallet-out bg-warning',
            'title' =>  "Oops, withdraw request cannot be cancelled!",
            'notice' => [
                'caption' => __("Sorry, your withdraw request can not be cancelled. Please contact administration."),
                'note' => (self::hasCode()) ? __('Error Reference ID :code', ['code' => '0x0315']) : '',
                'class' => ''
            ],
            'button' => [
                'text'  => __('Go to Dashboard'),
                'url'   => route('dashboard'),
                'class' => 'btn-primary'
            ],
            'help'  => self::helps('support')
        ];

        $messages['withdraw-no-rate'] = [
            'icon' => 'ni-alert bg-danger',
            'title' => __('Sorry, unable to proceed!'),
            'notice' => [
                'caption' => __("We are temporarily unable to process your withdrawal request. Please try again later."),
                'note'  => (self::hasCode()) ? __('Error Reference ID :code', ['code' => '0x0316']) : '',
                'class' => 'sm'
            ],
            'button' => [
                'text'  => __('Try Again'),
                'url'   => route('withdraw'),
                'class' => 'btn-primary'
            ],
            'link' =>  [
                'text'  => __('Go to Dashboard'),
                'url'   => route('dashboard'),
                'class' => 'link-primary'
            ],
            'help'  => self::helps('support')
        ];

        $messages['withdraw-invalid-action'] = [
            'icon' => 'ni-alert bg-warning',
            'title' => __('Sorry, Unable to proceed!'),
            'notice' => [
                'caption' => __("Sorry, we are unable to proceed your request. Please reload the page and try again."),
                'note'  => (self::hasCode()) ? __('Error Reference ID :code', ['code' => '0x0317']) : '',
                'class' => 'sm'
            ],
            'button' => false,
            'link' =>  [
                'text'  => __('Go to Dashboard'),
                'url'   => route('dashboard'),
                'class' => 'link-primary'
            ],
            'help'  => self::helps('support')
        ];

        $messages['withdraw-amount'] = [
            'icon' => 'ni-alert bg-danger',
            'title' => __('Sorry, unable to proceed!'),
            'notice' => [
                'caption' => __("We are temporarily unable to process your withdraw request. Please try again later."),
                'note'  => (self::hasCode()) ? __('Error Reference ID :code', ['code' => '0x0318']) : '',
                'class' => 'sm'
            ],
            'button' => [
                'text'  => __('Try Again'),
                'url'   => route('withdraw'),
                'class' => 'btn-primary'
            ],
            'link' =>  [
                'text'  => __('Go to Dashboard'),
                'url'   => route('dashboard'),
                'class' => 'link-primary'
            ],
            'help'  => self::helps('support')
        ];

        ////////////////////////////////////
        // Account related
        ////////////////////////////////////
        $messages['account-no-fund'] = [
        	'icon' => 'ni-wallet-saving bg-info',
            'title' =>  __('Insufficient Funds!'),
            'notice' => [
            	'caption' => __("You do not have any funds in your account to withdraw. Try again, once funds available."),
            	'note'	=> '',
            	'class'	=> 'sm'
            ],
            'button' => [
            	'text'	=> __('Go to Dashboard'),
            	'url'	=> route('dashboard'),
            	'class'	=> 'btn-primary'
            ],
            'link' =>  false,
            'help'	=> self::helps('simple-problem')
        ];

        $messages['account-add-method'] = [
        	'icon' => 'ni-wallet-out bg-info',
            'title' =>  "You're almost ready to withdraw!",
            'notice' => [
            	'caption' => __("To make a withdraw, please add a withdraw account from your profile (withdraw accounts)."),
            	'note' => '',
            	'class' => 'sm'
            ],
            'button' => [
            	'text'	=> __('Add Withdraw Account'),
            	'url'	=> route('account.withdraw-accounts'),
            	'class'	=> ''
            ],
            'link' =>  [
            	'text'	=> __('Go to Dashboard'),
            	'url'	=> route('dashboard'),
            	'class'	=> ''
            ],
            'help'	=> self::helps('simple-ask')
        ];

        $messages['account-less-fund'] = [
        	'icon' => 'ni-wallet-saving bg-warning',
            'title' =>  __('Oops, insufficient balance!'),
            'notice' => [
            	'caption' => __("Sorry, you do not have enough balance. Please withdraw the amount you have in your account."),
            	'note'	=> '',
            	'class'	=> 'sm'
            ],
            'button' => [
            	'text'	=> __('Try Again'),
            	'url'	=> route('withdraw'),
            	'class'	=> ''
            ],
            'link' =>  [
            	'text'	=> __('Go to Dashboard'),
            	'url'	=> route('dashboard'),
            	'class'	=> ''
            ],
            'help'	=> self::helps('simple-problem')
        ];

        $messages['account-invalid'] = [
            'icon' => 'ni-wallet-out bg-warning',
            'title' =>  "Oops, account not found!",
            'notice' => [
                'caption' => __("Sorry, your selected account (withdraw to) is no longer available. Choose another account & try again."),
                'note' => (self::hasCode()) ? __('Error Reference ID :code', ['code' => '0x0410']) : '',
                'class' => ''
            ],
            'button' => [
                'text'  => __('Try Again'),
                'url'   => route('withdraw'),
                'class' => ''
            ],
            'link' =>  [
                'text'  => __('Add Withdraw Account'),
                'url'   => route('account.withdraw-accounts'),
                'class' => ''
            ],
            'help'  => self::helps('support')
        ];

        $messages['account-verify-email'] = [
        	'icon' => 'ni-emails bg-warning',
            'title' =>  "Verify your email address",
            'notice' => [
            	'caption' => __("Your email address has not been verified! In order to deposit funds and avoid any service interruptions, you need to confirm your email address."),
            	'note' => '',
            	'class' => ''
            ],
            'button' => [
            	'text'	=> __('Verify Email Address'),
            	'url'	=> route('account.profile'),
            	'class'	=> ''
            ],
            'link' =>  [
            	'text'	=> __('Ask me later'),
            	'url'	=> route('dashboard'),
            	'class'	=> ''
            ],
            'help'	=> self::helps('simple-ask')
        ];

        ////////////////////////////////////
        // Invest related
        ////////////////////////////////////
        $messages['invest-disable'] = [
            'icon' => 'ni-alert bg-warning',
            'title' =>  sys_settings('iv_disable_title', __('Temporarily unavailable!')),
            'notice' => [
                'caption' => sys_settings('iv_disable_notice', __('Please check after sometimes and try again.')),
                'note'	=> '',
                'class'	=> ''
            ],
            'button' => [
                'text'	=> has_route('user.investment.dashboard') ? __('View Invested Plans') : __('Go to Dashboard'),
                'url'	=> has_route('user.investment.dashboard') ? route('user.investment.dashboard') : route('dashboard'),
                'class'	=> 'btn-primary'
            ],
            'link' =>  false,
            'help'	=> self::helps('simple-ask')
        ];

        $messages['invest-no-plan'] = [
            'icon' => 'ni-offer bg-info',
            'title' =>  __('Sorry, no plan available!'),
            'notice' => [
                'caption' => __("We regret that right now there is no investment plan available. Please check back later."),
                'note'  => '',
                'class' => ''
            ],
            'button' => [
                'text'  => has_route('user.investment.dashboard') ? __('Return to Investment') : __('Go to Dashboard'),
                'url'   => has_route('user.investment.dashboard') ? route('user.investment.dashboard') : route('dashboard'),
                'class' => 'btn-primary'
            ],
            'link' =>  [
                'text'  =>  has_route('user.investment.dashboard') ? __('Go to Dashboard') : '',
                'url'   =>  has_route('user.investment.dashboard') ? route('dashboard') : '',
                'class' => ''
            ],
            'help'  => self::helps('simple-ask')
        ];

        $messages['invest-no-funds'] = [
            'icon' => 'ni-wallet-saving bg-warning',
            'title' =>  __('Insufficient balance!'),
            'notice' => [
                'caption' => __("Sorry, you do not have sufficient balance in your account for investment. Please make a deposit and try again once you have sufficient balance."),
                'note'  => __("Deposit instantly using our available payment method."),
                'class' => ''
            ],
            'button' => [
                'text'  => __('Deposit Now'),
                'url'   => route('deposit'),
                'class' => 'btn-primary'
            ],
            'link' =>  [
                'text'  => has_route('user.investment.plans') ? __('Check our available plans') : __('Go to Dashboard'),
                'url'   => has_route('user.investment.plans') ? route('user.investment.plans') : route('dashboard'),
                'class' => ''
            ],
            'help'  => self::helps('simple-problem')
        ];

        $messages['invest-no-balance'] = [
            'icon' => 'ni-wallet-saving bg-info',
            'title' =>  __('No funds in account!'),
            'notice' => [
                'caption' => __("We regret that you have no funds in your account. Please make a deposit and try again once funds available."),
                'note'  => __("Deposit instantly using our available payment method."),
                'class' => ''
            ],
            'button' => [
                'text'  => __('Deposit Now'),
                'url'   => route('deposit'),
                'class' => 'btn-primary'
            ],
            'link' =>  [
                'text'  => __('Go to Dashboard'),
                'url'   => route('dashboard'),
                'class' => ''
            ],
            'help'  => self::helps('simple-problem')
        ];

        $messages['invest-wrong'] = [
            'icon' => 'ni-alert bg-danger',
            'title' => __('Sorry, unable to proceed!'),
            'notice' => [
                'caption' => __("We are temporarily unable to process your request. Please try again later."),
                'note'  => (self::hasCode()) ? __('Error Reference ID :code', ['code' => '0x0510']) : '',
                'class' => 'sm'
            ],
            'button' => [
                'text'  => __('Try Again'),
                'url'   => route('user.investment.invest'),
                'class' => 'btn-primary'
            ],
            'link' =>  [
                'text'  => __('Go to Dashboard'),
                'url'   => route('dashboard'),
                'class' => 'link-primary'
            ],
            'help'  => self::helps('support')
        ];

        ////////////////////////////////////
        // Misc related
        ////////////////////////////////////
        $messages['module-failed'] = [
            'icon' => 'ni-alert bg-danger',
            'title' => __('Sorry, unable to proceed!'),
            'notice' => [
                'caption' => __("We are temporarily unable to process your request. Please try again later."),
                'note'  =>  (self::hasCode()) ? __('Error Reference ID :code', ['code' => '0x7100']) : '',
                'class' => 'sm'
            ],
            'button' => [
                'text'  => __('Go to Dashboard'),
                'url'   => route('dashboard'),
                'class' => 'btn-primary'
            ],
            'link' =>  [],
            'help'  => self::helps('support')
        ];

        return $messages;
    }
}
