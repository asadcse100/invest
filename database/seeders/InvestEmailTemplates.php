<?php

namespace Database\Seeders;

use App\Enums\EmailRecipientType;
use App\Enums\EmailTemplateStatus;
use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class InvestEmailTemplates extends Seeder
{
    public function run()
    {
        $templates = [
            'investment-placed-customer' => [
                'name' => 'Investment Order Placed',
                'slug' => 'investment-placed-customer',
                'group' => 'investments',
                'recipient' => EmailRecipientType::CUSTOMER,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "off"],
                'subject' => 'Invest on [[plan_name]] ([[order_id]])',
                'greeting' => 'Hello [[user_name]],',
                'content' => "Thank you! You have invested the amount of [[invest_amount]] on '[[plan_name]]'. Your investment details are shown below for your reference:\n[[invest_details]] \n\nYour investment plan will start as soon as we have review and confirmed. \n\nFeel free to contact us if you have any questions.",
                'shortcut' => '[[order_id]], [[order_by]], [[order_time]], [[plan_name]], [[invest_amount]], [[invest_details]]',
            ],


            'investment-placed-admin' => [
                'name' => 'Investment Order Placed',
                'slug' => 'investment-placed-admin',
                'group' => 'investments',
                'recipient' => EmailRecipientType::ADMIN,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "off"],
                'subject' => 'Investment Plan ([[order_id]]) Purchased',
                'greeting' => 'Hello Admin,',
                'content' => "A new investment plan purchased by [[order_by]]. The investment details as follows: \n[[invest_details]] \n\nCustomer Details:\n[[user_detail]] \n\nThis is an automatic email confirmation, please check full order details in dashboard.\n\nThank You.",
                'shortcut' => '[[user_detail]], [[order_id]], [[order_by]], [[plan_name]], [[order_time]], [[invest_amount]], [[invest_details]]',
            ],


            'investment-approved-customer' => [
                'name' => 'Investment Order Approved',
                'slug' => 'investment-approved-customer',
                'group' => 'investments',
                'recipient' => EmailRecipientType::CUSTOMER,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "off"],
                'subject' => 'Investment plan ([[order_id]]) just started!',
                'greeting' => 'Dear [[user_name]],',
                'content' => "Congratulations! Your investment plan ([[order_id]]) approved and successfully started. \nYour investment details are shown below for your reference:\n[[invest_details]]\n\nIf you have any question, you can contact us at [[site_email]].",
                'shortcut' => '[[order_id]], [[order_by]], [[order_time]], [[plan_name]], [[invest_amount]], [[invest_details]]',
            ],


            'investment-approved-admin' => [
                'name' => 'Investment Order Approved',
                'slug' => 'investment-approved-admin',
                'group' => 'investments',
                'recipient' => EmailRecipientType::ADMIN,
                'status' => EmailTemplateStatus::INACTIVE,
                'params' => ["regards" => "off"],
                'subject' => 'Investment plan ([[order_id]]) just started!',
                'greeting' => 'Dear Admin,',
                'content' => "The investment order ([[order_id]]) has been approved and started. The investment details as follows: \n[[invest_details]] \n\nPS. Do not reply to this email.\nThank you.",
                'shortcut' => '[[order_id]], [[order_by]], [[order_time]], [[plan_name]], [[invest_amount]], [[invest_details]]',
            ],


            'investment-cancel-user-customer' => [
                'name' => 'Investment Cancelled by User',
                'slug' => 'investment-cancel-user-customer',
                'group' => 'investments',
                'recipient' => EmailRecipientType::CUSTOMER,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "off"],
                'subject' => 'Investment plan ([[order_id]]) has been cancelled!',
                'greeting' => 'Dear [[user_name]],',
                'content' => "You have cancelled your investment plan ([[order_id]]). The amount returned to your account balance. \n\nIf you want to invest again, please feel free to login into your account and choose a plan once again.\n\n\nIf you have any question, you can contact us at [[site_email]].",
                'shortcut' => '[[order_id]], [[order_by]], [[order_time]], [[plan_name]], [[invest_amount]], [[invest_details]]',
            ],


            'investment-cancel-user-admin' => [
                'name' => 'Investment Cancelled by User',
                'slug' => 'investment-cancel-user-admin',
                'group' => 'investments',
                'recipient' => EmailRecipientType::ADMIN,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "off"],
                'subject' => 'Investment plan ([[order_id]]) successfully cancelled!',
                'greeting' => 'Dear Admin,',
                'content' => "The recent investment plan ([[order_id]]) has been cancelled by [[order_by]]. The invested amount returned to user's account balance. \n\n\nThis is an automatic email confirmation, no further action is needed.\n\nPS. Do not reply to this email.\nThank you.",
                'shortcut' => '[[order_id]], [[order_by]], [[order_time]], [[plan_name]], [[invest_amount]], [[invest_details]]',
            ],


            'investment-cancel-customer' => [
                'name' => 'Investment Cancelled by Admin',
                'slug' => 'investment-cancel-customer',
                'group' => 'investments',
                'recipient' => EmailRecipientType::CUSTOMER,
                'status' => EmailTemplateStatus::INACTIVE,
                'params' => ["regards" => "off"],
                'subject' => 'Investment plan ([[order_id]]) has been cancelled!',
                'greeting' => 'Dear [[user_name]],',
                'content' => "Your recent investment plan ([[order_id]]) has been cancelled. The invested amount returned to your account balance. \n\nIf you want to invest again, please feel free to login into your account and choose a plan once again.\n\n\nIf you have any question, you can contact us at [[site_email]].",
                'shortcut' => '[[order_id]], [[order_by]], [[order_time]], [[plan_name]], [[invest_amount]], [[invest_details]]',
            ],


            'investment-cancel-admin' => [
                'name' => 'Investment Cancelled by Admin',
                'slug' => 'investment-cancel-admin',
                'group' => 'investments',
                'recipient' => EmailRecipientType::ADMIN,
                'status' => EmailTemplateStatus::INACTIVE,
                'params' => ["regards" => "off"],
                'subject' => 'Investment plan ([[order_id]]) successfully cancelled!',
                'greeting' => 'Dear Admin,',
                'content' => "The investment order ([[order_id]]) has been cancelled. The invested amount returned to user's account balance. \n\nPS. Do not reply to this email.\nThank you.",
                'shortcut' => '[[order_id]], [[order_by]], [[order_time]], [[plan_name]], [[invest_amount]], [[invest_details]]',
            ],


            'investment-cancellation-customer' => [
                'name' => 'Investment Cancellation by Admin',
                'slug' => 'investment-cancellation-customer',
                'group' => 'investments',
                'recipient' => EmailRecipientType::CUSTOMER,
                'status' => EmailTemplateStatus::ACTIVE,
                'params' => ["regards" => "off"],
                'subject' => 'Investment Cancellation ([[order_id]])',
                'greeting' => 'Dear [[user_name]],',
                'content' => "We are sorry to inform you that we've cancelled your investment plan of ([[plan_name]]). We have settlement your investment account balance. Please login into your account and check your account balance.\n\n\nIf you have any question about cancellation, please feel free to contact us.\n\nPS. Do not reply to this email.\nThank you.",
                'shortcut' => '[[order_id]], [[order_by]], [[order_time]], [[plan_name]], [[invest_amount]], [[invest_details]]',
            ],


            'investment-cancellation-admin' => [
                'name' => 'Investment Cancellation by Admin',
                'slug' => 'investment-cancellation-admin',
                'group' => 'investments',
                'recipient' => EmailRecipientType::ADMIN,
                'status' => EmailTemplateStatus::INACTIVE,
                'params' => ["regards" => "off"],
                'subject' => 'Investment Cancellation ([[order_id]])',
                'greeting' => 'Dear [[user_name]],',
                'content' => "The investment plan of ([[plan_name]] - [[order_id]]) has been cancelled. User account balance adjusted with invested amount.\n\nPS. Do not reply to this email.\nThank you.",
                'shortcut' => '[[order_id]], [[order_by]], [[order_time]], [[plan_name]], [[invest_amount]], [[invest_details]]',
            ]
        ];

        if (empty($templates)) {
            return;
        }

        foreach ($templates as $slug => $template) {
            $exist = EmailTemplate::where('slug', $slug)->count();
            if ($exist <= 0) {
                EmailTemplate::create($template);
            }
        }
    }
}
