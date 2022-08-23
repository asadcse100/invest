<?php

namespace Database\Seeders;

use App\Enums\EmailRecipientType;
use App\Enums\EmailTemplateStatus;
use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplatesSeeder extends Seeder
{
    public function run()
    {
        $templates = [
            'users-confirm-email' => [
                'name' => 'Email Confirmation',
                'slug' => 'users-confirm-email',
                'group' => 'authentication',
                'recipient' => EmailRecipientType::CUSTOMER,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "on"],
                'subject' => 'Verify Your Email Address - [[site_name]]',
                'greeting' => 'Welcome [[user_name]]!',
                'content' => "Thank you for registering on our platform. You're almost ready to start.\n\nSimply click the button below to confirm your email address and active your account.",
                'shortcut' => '',
            ],

            'users-welcome-email' => [
                'name' => 'Welcome Email',
                'slug' => 'users-welcome-email',
                'group' => 'authentication',
                'recipient' => EmailRecipientType::CUSTOMER,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "on"],
                'subject' => 'Welcome to [[site_name]]',
                'greeting' => 'Hi [[user_name]],',
                'content' => "Thanks for joining our platform! \n\nAs a member of our platform, you can mange your account, buy or sell cryptocurrency. \n\nFind out more about in - [[site_url]]",
                'shortcut' => '',
            ],

            'users-reset-password' => [
                'name' => 'Password Reset by User',
                'slug' => 'users-reset-password',
                'group' => 'authentication',
                'recipient' => EmailRecipientType::CUSTOMER,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "on"],
                'subject' => 'Password Reset Request on [[site_name]]',
                'greeting' => 'Hi [[user_name]],',
                'content' => "<strong>You told us you forgot your password.</strong> \n\nIf you really forgot, click the below button to reset your password. \n\nIf you did not make reset request, then you can just ignore this email; your password will not change.",
                'shortcut' => '',
            ],

            'users-change-password-success' => [
                'name' => 'Password Changed Successfully',
                'slug' => 'users-change-password-success',
                'group' => 'authentication',
                'recipient' => EmailRecipientType::CUSTOMER,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "on"],
                'subject' => 'Your Password Has Been Changed',
                'greeting' => 'Hi [[user_name]],',
                'content' => "This email is to confirm that your account password has been successfully changed. If you did not request a password change, please contact us immediately.",
                'shortcut' => '',
            ],

            'users-change-email' => [
                'name' => 'Email Changed by User',
                'slug' => 'users-change-email',
                'group' => 'authentication',
                'recipient' => EmailRecipientType::CUSTOMER,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "on"],
                'subject' => 'Verify Your New Email Address - [[site_name]]',
                'greeting' => 'Hi [[user_name]],',
                'content' => "<strong>There was a request to change your email address.</strong> \n\nIf you really want to change your email, simply click the button below to confirm your new email address. \n\nIf you did not make this change, then you can just ignore this email; your email will not change.",
                'shortcut' => '',
            ],

            'users-change-email-success' => [
                'name' => 'Email Changed Successfully',
                'slug' => 'users-change-email-success',
                'group' => 'authentication',
                'recipient' => EmailRecipientType::CUSTOMER,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "on"],
                'subject' => 'Email Address Has Been Changed',
                'greeting' => 'Hi [[user_name]],',
                'content' => "This email is to confirm that your account email address has been successfully changed. Now you can login at [[site_url]] with your new email address. If you did not make this change, please contact us immediately.",
                'shortcut' => '',
            ],

            'users-unusual-login' => [
                'name' => 'Unusual Login Email',
                'slug' => 'users-unusual-login',
                'group' => 'authentication',
                'recipient' => EmailRecipientType::CUSTOMER,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "on"],
                'subject' => 'Unusual Login Attempt on [[site_name]]',
                'greeting' => 'Hi [[user_name]],',
                'content' => "We noticed you're having trouble logging into your account. There was few unsuccessful login attempt on your account. If this wasn't you, let us know.",
                'shortcut' => '',
            ],

            'users-admin-reset-password' => [
                'name' => 'Password Reset by Admin',
                'slug' => 'users-admin-reset-password',
                'group' => 'authentication',
                'recipient' => EmailRecipientType::CUSTOMER,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "on"],
                'subject' => 'Your Password is reseted on [[site_name]]',
                'greeting' => 'Hi [[user_name]],',
                'content' => "We have reset your login password as per your requested via support. Now you can login at [[site_url]] with new password as below.",
                'shortcut' => '',
            ],

            'user-registration-admin' => [
                'name' => 'Welcome Email',
                'slug' => 'user-registration-admin',
                'group' => 'authentication',
                'recipient' => EmailRecipientType::CUSTOMER,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "on"],
                'subject' => 'Welcome to [[site_name]]',
                'greeting' => 'Hi [[user_name]],',
                'content' => "You are receiving this email because you have registered on our site.",
                'shortcut' => '',
            ],

//            'users-admin-reset-2fa' => [
//                'name' => '2FA Disable by Admin',
//                'slug' => 'users-admin-reset-2fa',
//                'group' => 'authentication',
//                'recipient' => EmailRecipientType::CUSTOMER,
//                'status' => EmailTemplateStatus::ACTIVE,
//                'params' => ["regards" => "on"],
//                'subject' => 'Disable 2FA Authentication Request',
//                'greeting' => 'Hi [[user_name]],',
//                'content' => "We have reset your 2FA authentication as per your requested via support.\n\n If you really want to reset 2FA authentication security in your account, then click the button below to confirm and reset 2FA authentication on your account.",
//                'shortcut' => '',
//            ],

//            'users-admin-delete-account' => [
//                'name' => 'User Account Delete by Admin',
//                'slug' => 'users-admin-delete-account',
//                'group' => 'account',
//                'recipient' => EmailRecipientType::CUSTOMER,
//                'status' => EmailTemplateStatus::ACTIVE,
//                'params' => ["regards" => "on"],
//                'subject' => 'Account Bas Been Deleted',
//                'greeting' => 'Hi,',
//                'content' => "This message confirms that your account [[user_email]] was deleted. \n\nYour account can not restore any more. If you have any questions please feel free to contact us.",
//                'shortcut' => '',
//            ],

            //// DEPOSIT
            ///////////////////////////////////////

            'deposit-placed-customer' => [
                'name' => 'Deposit Order Placed',
                'slug' => 'deposit-placed-customer',
                'group' => 'deposits',
                'recipient' => EmailRecipientType::CUSTOMER,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "off"],
                'subject' => 'New Deposit #[[order_id]]',
                'greeting' => 'Hello [[user_name]],',
                'content' => "Your deposit order has been placed and is now being waiting for payment. Your deposit details are shown below for your reference:\n[[order_details]]\n[[payment_information]]\n\nYour funds will add into your account as soon as we have confirmed the payment. \n\nFeel free to contact us if you have any questions.",
                'shortcut' => '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[order_details]], [[payment_method]], [[payment_information]]',
            ],

            'deposit-placed-admin' => [
                'name' => 'Deposit Order Placed',
                'slug' => 'deposit-placed-admin',
                'group' => 'deposits',
                'recipient' => EmailRecipientType::ADMIN,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "off"],
                'subject' => 'New Deposit #[[order_id]] by [[order_by]]',
                'greeting' => 'Hello Admin,',
                'content' => "You have received an deposit order from [[order_by]]. The deposit order is as follows: \n[[order_details]] \n\nCustomer Details:\n[[user_detail]] \n\nThis is an automatic email confirmation, please check full order details in dashboard.\n\nThank You.",
                'shortcut' => '[[user_detail]], [[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[order_details]], [[payment_method]]',
            ],

            'deposit-cancel-user-customer' => [
                'name' => 'Deposit Cancelled by User',
                'slug' => 'deposit-cancel-user-customer',
                'group' => 'deposits',
                'recipient' => EmailRecipientType::CUSTOMER,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "off"],
                'subject' => 'Deposit has been cancelled!',
                'greeting' => 'Dear [[user_name]],',
                'content' => "Your recent deposit (#[[order_id]]) has been cancelled. \n\nIf you want to deposit funds into your again, please feel free to login into your account and add funds once again.\n\n\nIf you have any question, you can contact us at [[site_email]].",
                'shortcut' => '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[order_details]], [[payment_method]]',
            ],

            'deposit-cancel-user-admin' => [
                'name' => 'Deposit Cancelled by User',
                'slug' => 'deposit-cancel-user-admin',
                'group' => 'deposits',
                'recipient' => EmailRecipientType::ADMIN,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "off"],
                'subject' => 'Deposit #[[order_id]] has been cancelled',
                'greeting' => 'Dear Admin,',
                'content' => "The recent deposit order (#[[order_id]]) has been cancelled by [[order_by]]. \n\n\nThis is an automatic email confirmation, no need any action for further.\n\nPS. Do not reply to this email.\nThank you.",
                'shortcut' => '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[order_details]], [[payment_method]]',
            ],

            'deposit-cancel-gateway-customer' => [
                'name' => 'Deposit Cancelled by Gateway',
                'slug' => 'deposit-cancel-gateway-customer',
                'group' => 'deposits',
                'recipient' => EmailRecipientType::CUSTOMER,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "off"],
                'subject' => 'Payment Rejected - Deposit #[[order_id]]',
                'greeting' => 'Dear [[user_name]],',
                'content' => "The deposit (#[[order_id]]) has been canceled, however the payment was not successful and [[payment_method]] rejected or cancelled the payment.\n\n\nIf you have any question, you can contact us at [[site_email]].\n\nPS. Do not reply to this email.\nThank you.",
                'shortcut' => '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[order_details]], [[payment_method]]',
            ],

            'deposit-cancel-gateway-admin' => [
                'name' => 'Deposit Cancelled by Gateway',
                'slug' => 'deposit-cancel-gateway-admin',
                'group' => 'deposits',
                'recipient' => EmailRecipientType::ADMIN,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "off"],
                'subject' => 'Deposit #[[order_id]] has been cancelled',
                'greeting' => 'Dear Admin,',
                'content' => "The recent deposit order (#[[order_id]]) has been cancelled by [[payment_method]], however the payment was not made. \n\n\nThis is an automatic email confirmation, no need any action for further.\n\nPS. Do not reply to this email.\nThank you.",
                'shortcut' => '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[order_details]], [[payment_method]]',
            ],

            'deposit-reject-customer' => [
                'name' => 'Deposit Rejected by Admin',
                'slug' => 'deposit-reject-customer',
                'group' => 'deposits',
                'recipient' => EmailRecipientType::CUSTOMER,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "off"],
                'subject' => 'Cancelled Deposit #[[order_id]]',
                'greeting' => 'Dear [[user_name]],',
                'content' => "The deposit (#[[order_id]]) has been cancelled, however we have not received your payment of [[order_amount]] (via [[payment_method]]).\n\n\nIf you have any question, you can contact us at [[site_email]].\n\nPS. Do not reply to this email.\nThank you.",
                'shortcut' => '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]] , [[order_time]], [[order_details]], [[payment_method]]',
            ],

            'deposit-reject-admin' => [
                'name' => 'Deposit Rejected by Admin',
                'slug' => 'deposit-reject-admin',
                'group' => 'deposits',
                'recipient' => EmailRecipientType::ADMIN,
                'status' => EmailTemplateStatus::INACTIVE,
                'params' => ["regards" => "off"],
                'subject' => 'Deposit #[[order_id]] has been cancelled',
                'greeting' => 'Dear Admin,',
                'content' => "The deposit order (#[[order_id]]) has been cancelled. \n\nPS. Do not reply to this email.\nThank you.",
                'shortcut' => '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[order_details]], [[payment_method]]',
            ],

            'deposit-approved-customer' => [
                'name' => 'Deposit Order Approved',
                'slug' => 'deposit-approved-customer',
                'group' => 'deposits',
                'recipient' => EmailRecipientType::CUSTOMER,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "off"],
                'subject' => 'Deposit successfully processed!',
                'greeting' => 'Dear [[user_name]],',
                'content' => "Your deposit of [[order_amount]] has been successfully approved. \nThis email confirms that funds have been added to your account.\n\nIf you have any question, you can contact us at [[site_email]].",
                'shortcut' => '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[order_details]], [[payment_method]]',
            ],

            'deposit-approved-admin' => [
                'name' => 'Deposit Order Approved',
                'slug' => 'deposit-approved-admin',
                'group' => 'deposits',
                'recipient' => EmailRecipientType::ADMIN,
                'status' => EmailTemplateStatus::INACTIVE,
                'params' => ["regards" => "off"],
                'subject' => 'Deposit Successfull - Order #[[order_id]]',
                'greeting' => 'Dear Admin,',
                'content' => "The deposit order (#[[order_id]]) has been approved and funds of [[order_amount]] added into user account. \n\nPS. Do not reply to this email.\nThank you.",
                'shortcut' => '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[order_details]], [[payment_method]]',
            ],

            'deposit-success-gateway-customer' => [
                'name' => 'Deposit Success by Gateway',
                'slug' => 'deposit-success-gateway-customer',
                'group' => 'deposits',
                'recipient' => EmailRecipientType::CUSTOMER,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "off"],
                'subject' => 'Deposit successfully processed!',
                'greeting' => 'Dear [[user_name]],',
                'content' => "Your deposit of [[order_amount]] has been successfully. \nThis email confirms that funds have been added to your account.\n\nIf you have any question, you can contact us at [[site_email]].",
                'shortcut' => '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[order_details]], [[payment_method]]',
            ],

            'deposit-success-gateway-admin' => [
                'name' => 'Deposit Success by Gateway',
                'slug' => 'deposit-success-gateway-admin',
                'group' => 'deposits',
                'recipient' => EmailRecipientType::ADMIN,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "off"],
                'subject' => 'Deposit Successfull - Order #[[order_id]]',
                'greeting' => 'Dear Admin,',
                'content' => "You just received a payment of [[order_amount]] for deposit order (#[[order_id]]) via [[payment_method]]. \n\nPS. Do not reply to this email.\nThank you.",
                'shortcut' => '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[order_details]], [[payment_method]]',
            ],

            'deposit-refund-customer' => [
                'name' => 'Deposit Refund by Admin',
                'slug' => 'deposit-refund-customer',
                'group' => 'deposits',
                'recipient' => EmailRecipientType::CUSTOMER,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "off"],
                'subject' => 'Your deposit (#[[order_id]]) has been refunded!',
                'greeting' => 'Hello [[user_name]],',
                'content' => "We have refunded your funds and re-adjusted your account balance. Please find below your refund and original deposit details. \n\nPS. Do not reply to this email.\nThank you.",
                'shortcut' => '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[order_details]], [[payment_method]], [[refund_details]]',
            ],

            'deposit-refund-admin' => [
                'name' => 'Deposit Refund by Admin',
                'slug' => 'deposit-refund-admin',
                'group' => 'deposits',
                'recipient' => EmailRecipientType::ADMIN,
                'status' => EmailTemplateStatus::INACTIVE,
                'params' => ["regards" => "off"],
                'subject' => 'Refunded Deposit #[[order_id]]',
                'greeting' => 'Hello Admin,',
                'content' => "The deposit order (#[[order_id]]) refunded successfully. The user account balance adjusted with refund amount of [[order_amount]]. \n\nPS. Do not reply to this email.\nThank you.",
                'shortcut' => '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[order_details]], [[payment_method]], [[refund_details]]',
            ],

            //// WITHDRAWAL
            ///////////////////////////////////////

            'withdraw-request-customer' => [
                'name' => 'Withdraw Request',
                'slug' => 'withdraw-request-customer',
                'group' => 'withdrawal',
                'recipient' => EmailRecipientType::CUSTOMER,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "on"],
                'subject' => 'Your Withdraw Request Has Been Received',
                'greeting' => 'Hello [[user_name]],',
                'content' => "We received your request to withdraw funds from [[site_name]]. The funds will be deposited in your provided account and should be processed with 24-72 hours. You will be notified by email when we have completed your withdraw.\n\nWithdrawal Details:\n[[withdraw_details]]\n\nNote: If you did not make this withdraw request, please contact us immediately before its authorized by our team.\n\nIf you have any questions, please feel free to contact us.\n",
                'shortcut' => '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[payment_method]], [[withdraw_to]], [[withdraw_details]]',
            ],

            'withdraw-request-admin' => [
                'name' => 'Withdraw Request',
                'slug' => 'withdraw-request-admin',
                'group' => 'withdrawal',
                'recipient' => EmailRecipientType::ADMIN,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "off"],
                'subject' => 'Withdraw Request from [[user_name]]',
                'greeting' => 'Hello Admin,',
                'content' => "A user ([[user_name]] - [[user_email]]) requested to withdraw funds. Please review the withdraw request as soon as possible.\n[[withdraw_details]]\n\nPlease login into account and take necessary steps for withdraw.\n\n\nPS. Do not reply to this email.\nThank you.\n",
                'shortcut' => '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[payment_method]], [[withdraw_to]], [[withdraw_details]]',
            ],

            'withdraw-cancel-user-customer' => [
                'name' => 'Withdraw Cancel Request',
                'slug' => 'withdraw-cancel-user-customer',
                'group' => 'withdrawal',
                'recipient' => EmailRecipientType::CUSTOMER,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "on"],
                'subject' => 'Withdraw Has Been Cancelled!',
                'greeting' => 'Hello [[user_name]],',
                'content' => "Your recent withdraw request (#[[order_id]]) has been cancelled. The funds returned to your account balance.\n\nIf you want to withdraw funds into your account again, please feel free to login into your account and withdraw once again.\n\nIf you have any questions, please feel free to contact us.\n",
                'shortcut' => '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[payment_method]], [[withdraw_to]], [[withdraw_details]]',
            ],

            'withdraw-cancel-user-admin' => [
                'name' => 'Withdraw Cancel Request',
                'slug' => 'withdraw-cancel-user-admin',
                'group' => 'withdrawal',
                'recipient' => EmailRecipientType::ADMIN,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "off"],
                'subject' => 'Cancelled Withdraw Request by [[user_name]]',
                'greeting' => 'Hello Admin,',
                'content' => "The recent withdraw request (#[[order_id]]) has been cancelled by user ([[user_name]] - [[user_email]]). \nYou do not need to take any further action.\n\n\nPS. Do not reply to this email.\nThank you.\n",
                'shortcut' => '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[payment_method]], [[withdraw_to]], [[withdraw_details]]',
            ],

            'withdraw-confirmed-customer' => [
                'name' => 'Withdraw Order Confirmed',
                'slug' => 'withdraw-confirmed-customer',
                'group' => 'withdrawal',
                'recipient' => EmailRecipientType::CUSTOMER,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "on"],
                'subject' => 'Withdrawal Successfully Confirmed!',
                'greeting' => 'Dear [[user_name]],',
                'content' => "Your withdraw request of [[order_amount]] has been successfully confirmed. \nThis email confirms that your desired amount will deposited in your account ([[withdraw_to]]) within few hours.\n\nIf you have any question, you can contact us at [[site_email]].",
                'shortcut' => '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[payment_method]], [[withdraw_to]], [[withdraw_details]]',
            ],

            'withdraw-confirmed-admin' => [
                'name' => 'Withdraw Order Confirmed',
                'slug' => 'withdraw-confirmed-admin',
                'group' => 'withdrawal',
                'recipient' => EmailRecipientType::ADMIN,
                'status' => EmailTemplateStatus::INACTIVE,
                'params' => ["regards" => "off"],
                'subject' => 'Withdraw Request #[[order_id]] Has Been Confirmed',
                'greeting' => 'Hello Admin,',
                'content' => "The withdraw request (#[[order_id]]) has been confirmed and notified to user ([[user_name]] - [[user_email]]). Withdraw amount of [[order_amount]] need to be processed for this user.\n\nPS. Do not reply to this email.\nThank you.",
                'shortcut' => '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[payment_method]], [[withdraw_to]], [[withdraw_details]]',
            ],

            'withdraw-success-customer' => [
                'name' => 'Withdraw Processed Request',
                'slug' => 'withdraw-success-customer',
                'group' => 'withdrawal',
                'recipient' => EmailRecipientType::CUSTOMER,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "on"],
                'subject' => 'Your Withdraw Request Has Been Completed',
                'greeting' => 'Hello [[user_name]],',
                'content' => "<strong>Congratulations!</strong>\n\nYour withdraw request (#[[order_id]]) has been successfully processed and a total amount of <strong>[[order_amount]]</strong> has been withdrawn from your account. Your funds transferred into your account as below. \n\nPayment Deposited: \n<strong>[[withdraw_to]]</strong> ([[payment_method]]).\n\nWithdraw Reference: \n[[withdraw_reference]]\n[[withdraw_note]]\n\nIf you have not received funds into your account yet, please feel free to contact us.\n",
                'shortcut' => '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[payment_method]], [[withdraw_to]], [[withdraw_details]], [[withdraw_reference]], [[withdraw_note]]',
            ],

            'withdraw-success-admin' => [
                'name' => 'Withdraw Processed Request',
                'slug' => 'withdraw-success-admin',
                'group' => 'withdrawal',
                'recipient' => EmailRecipientType::ADMIN,
                'status' => EmailTemplateStatus::INACTIVE,
                'params' => ["regards" => "off"],
                'subject' => 'Withdraw Request #[[order_id]] Has Been Processed',
                'greeting' => 'Hello Admin,',
                'content' => "The withdraw request (#[[order_id]]) has been processed and notified to user ([[user_name]] - [[user_email]]). \n\nWithdraw Details:\n[[withdraw_details]] \n\nWithdraw Reference:\n[[withdraw_reference]] \n[[withdraw_note]]\n\nPS. Do not reply to this email.\nThank you.",
                'shortcut' => '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[payment_method]], [[withdraw_to]], [[withdraw_details]], [[withdraw_reference]], [[withdraw_note]]',
            ],

            'withdraw-reject-customer' => [
                'name' => 'Withdraw Rejected by Admin',
                'slug' => 'withdraw-reject-customer',
                'group' => 'withdrawal',
                'recipient' => EmailRecipientType::CUSTOMER,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "on"],
                'subject' => 'Withdraw Request Has Been Rejected',
                'greeting' => 'Hello [[user_name]],',
                'content' => "We have received your request (#[[order_id]]) to withdraw funds. We would like to inform you that we have cancelled this request and the funds ([[order_all_amount]]) returned to your account balance.\n\nWithdraw request has been rejected for following reason -\n[[withdraw_note]]\n\nIf you have any questions, please feel free to contact us.",
                'shortcut' => '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[payment_method]], [[withdraw_to]], [[withdraw_details]], [[withdraw_note]]',
            ],

            'withdraw-reject-admin' => [
                'name' => 'Withdraw Rejected by Admin',
                'slug' => 'withdraw-reject-admin',
                'group' => 'withdrawal',
                'recipient' => EmailRecipientType::ADMIN,
                'status' => EmailTemplateStatus::INACTIVE,
                'params' => ["regards" => "off"],
                'subject' => 'Withdraw Request Has Been Rejected',
                'greeting' => 'Hello [[user_name]],',
                'content' => "The withdraw request (#[[order_id]]) has been rejected. The amount of [[order_all_amount]] has been adjusted to user account balance and notified to user ([[user_name]] - [[user_email]]). \n\nRejection Note: \n[[withdraw_note]]\n\nPS. Do not reply to this email.\nThank you.",
                'shortcut' => '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[payment_method]], [[withdraw_to]], [[withdraw_details]], [[withdraw_note]]',
            ],

        ];

        foreach ($templates as $slug => $template) {
            $exist = EmailTemplate::where('slug', $slug)->count();
            if ($exist <= 0) {
                EmailTemplate::create($template);
            }
        }
    }
}
