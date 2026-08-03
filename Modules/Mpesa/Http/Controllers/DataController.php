<?php

namespace Modules\Mpesa\Http\Controllers;

use App\Http\Controllers\Controller;
use Menu;
class DataController extends Controller
{
    /**
     * Called by core UltimatePOS's SellPosController for every POS-related screen
     * (ModuleUtil::getModuleData('get_pos_screen_view')). Returning a view path
     * here as "module_js_path" is how core lets modules add JS/markup to the POS
     * screen without modifying any core blade files - core itself does:
     *
     *   @includeIf($value['module_js_path'], ['view_data' => $value['view_data']])
     *
     * inside create.blade.php's @section('javascript'). This is what makes the
     * M-Pesa button + modal actually appear on the sell screen.
     */
    public function get_pos_screen_view($args = null)
    {
        // Don't attempt to render anything if the module hasn't been migrated yet
        // (e.g. just uploaded via Manage Modules but "Install" hasn't run).
        if (!\Schema::hasTable('mpesa_settings')) {
            return [];
        }

        return [
            'module_js_path' => 'mpesa::partials.pos_button',
            'module_css_path' => '',
            'view_data' => $args,
        ];
    }

    /**
     * Permissions introduced by this module.
     * These become available on the Roles & Permissions screen.
     */
    public function user_permissions()
    {
        return [
            [
                'value' => 'mpesa.settings',
                'label' => __('mpesa::lang.manage_mpesa_settings'),
                'default' => false,
            ],
            [
                'value' => 'mpesa.view_transactions',
                'label' => __('mpesa::lang.view_mpesa_transactions'),
                'default' => false,
            ],
            [
                'value' => 'mpesa.collect_payment',
                'label' => __('mpesa::lang.collect_mpesa_payment'),
                'default' => true,
            ],
        ];
    }

    /**
     * Add "M-Pesa" menu items to the admin sidebar.
     * Mirrors the WooCommerce module's modifyAdminMenu(): a top-level entry
     * (via $menu->url()) that's always added once the module is migrated -
     * no separate "install" gate in the menu itself.
     */
    public function modifyAdminMenu()
    {
        // Safety net: if for some reason the tables don't exist yet
        // (module uploaded but not migrated), don't add menu items that
        // would error out when clicked.
        if (!\Schema::hasTable('mpesa_settings')) {
            return;
        }

        Menu::modify('admin-sidebar-menu', function ($menu) {
            $menu->dropdown(__('mpesa::lang.mpesa'), function ($sub) {
                $sub->url(
                    action('\Modules\Mpesa\Http\Controllers\SettingController@index'),
                    __('mpesa::lang.settings'),
                    ['icon' => 'fa fa-cog', 'active' => request()->is('mpesa/settings*')]
                );

                $sub->url(
                    action('\Modules\Mpesa\Http\Controllers\MpesaController@transactions'),
                    __('mpesa::lang.transactions'),
                    ['icon' => 'fa fa-money', 'active' => request()->is('mpesa/transactions*')]
                );
            }, ['icon' => 'fa fa-mobile', 'active' => request()->is('mpesa*')])->order(89);
        });
    }

    /**
     * Format notifications related to M-Pesa payments (e.g. a payment confirmed
     * after the cashier has moved on from the sale screen).
     */
    public function parse_notification($notification)
    {
        $notification_data = null;

        if ($notification->type == 'Modules\Mpesa\Notifications\PaymentReceivedNotification') {
            $data = $notification->data;

            $notification_data = [
                'msg' => __('mpesa::lang.payment_received_notification', [
                    'amount' => $data['amount'] ?? '',
                    'receipt' => $data['receipt'] ?? '',
                ]),
                'icon_class' => 'fa fa-money',
                'link' => action('\Modules\Mpesa\Http\Controllers\MpesaController@transactions'),
                'read_at' => $notification->read_at,
                'created_at' => $notification->created_at->diffForHumans(),
            ];
        }

        return $notification_data;
    }
}
