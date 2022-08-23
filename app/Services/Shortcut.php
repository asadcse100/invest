<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\IvInvest;
use App\Models\User;
use App\Enums\TransactionType;

use Illuminate\Support\Facades\URL;

class Shortcut extends Service
{
    private $shortcuts = [];

    public function __construct()
    {
        $this->setDefaultShortcuts();
    }

    private function setDefaultShortcuts()
    {
        $defaultShortcuts = [
            "[[site_name]]" => sys_settings("site_name"),
            "[[site_url]]" => URL::to('/'),
            "[[site_email]]" => sys_settings('site_email'),
        ];

        $this->shortcuts = array_merge($this->shortcuts, $defaultShortcuts);

        return $this;
    }

    public function setOrderShortcuts($order)
    {
        if (!blank($order)) {
            if ($order instanceof Transaction) {
                $orderShortcuts = [
                    "[[order_id]]" => the_tnx($order->tnx),
                    "[[order_by]]" => $order->transaction_by->username,
                    "[[order_time]]" => show_date($order->created_at),
                    "[[order_amount]]" => money($order->tnx_amount, $order->tnx_currency),
                    "[[order_all_amount]]" => ($order->currency == $order->tnx_currency) ? money($order->amount, $order->currency) : money($order->tnx_amount, $order->tnx_currency) . ' (' . money($order->amount, $order->currency) . ')',
                    "[[order_details]]" => $this->generateDetailTable($order->shortcut_details),
                    "[[withdraw_details]]" => $this->generateDetailTable($order->shortcut_details),
                    "[[withdraw_to]]" => $order->pay_to,
                    "[[payment_method]]" => $order->method_name,
                    "[[withdraw_reference]]" => $order->reference,
                    "[[withdraw_note]]" => $order->note,
                    "[[payment_information]]" => $order->payment_info,
                ];

                if ($order->type == TransactionType::TRANSFER) {
                    $orderShortcutsTransfer = [
                        "[[transfer_details]]" => $this->generateDetailTable($order->shortcut_details),
                        "[[user_to_name]]" => get_user(data_get($order, 'meta.transfer.user'))->username,
                        "[[user_to_email]]" => get_user(data_get($order, 'meta.transfer.user'))->email,
                        "[[user_from_name]]" => get_user(data_get($order, 'user_id'))->username,
                        "[[user_from_email]]" => get_user(data_get($order, 'user_id'))->email,
                    ];
                    $orderShortcuts = array_merge($orderShortcutsTransfer, $orderShortcuts);
                }
            }

            if ($order instanceof IvInvest) {
                $orderShortcuts = [
                    "[[order_id]]" => the_inv($order->ivx),
                    "[[order_by]]" => $order->user->username,
                    "[[order_time]]" => show_date($order->created_at),
                    "[[plan_name]]" => data_get($order->scheme, 'name'),
                    "[[invest_amount]]" => money($order->amount, $order->currency),
                    "[[invest_details]]" => $order->getSummaryTitleAttribute()
                ];
            }

            $this->shortcuts = array_merge($this->shortcuts, $orderShortcuts);
        }

        return $this;
    }

    private function generateDetailTable($data)
    {
        if (empty($data)) {
            return "";
        }

        $table = '<table width="100%">';
        foreach ($data as $key => $value) {
            $table .= $value ? '<tr><td width="150">' . ucwords(str_replace('_', ' ', $key)) . '</td><td width="25">&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>'. $value .'</td></tr>' : '';
        }
        $table .= '</table>';

        return $table;
    }

    public function setUserShortcuts(User $user)
    {
        $userShortcuts = [
            '[[user_id]]' => the_uid($user->id),
            "[[user_name]]" => $user->name,
            "[[user_email]]" => $user->email,
            "[[user_detail]]" => $this->generateDetailTable($user->shortcut_details ?? []),
        ];

        $this->shortcuts = array_merge($this->shortcuts, $userShortcuts);

        return $this;
    }

    public function processContent(string $content)
    {
        return strtr($content, $this->shortcuts);
    }
}
