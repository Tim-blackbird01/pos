<?php

namespace Modules\Mpesa\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Mpesa\Entities\MpesaSetting;
use Modules\Mpesa\Services\DarajaService;

class SettingController extends Controller
{
    /**
     * Show the M-Pesa settings form for the logged-in business.
     */
    public function index()
    {
        if (!auth()->user()->can('mpesa.settings')) {
            abort(403, 'Unauthorized action.');
        }

        // Safety net: if the module's tables don't exist yet, the migration
        // (which runs when the module is uploaded/enabled) hasn't completed.
        if (!\Schema::hasTable('mpesa_settings')) {
            abort(404, 'M-Pesa module is not fully installed yet. Please re-run "module:migrate Mpesa" or re-upload the module via Manage Modules.');
        }

        $business_id = request()->session()->get('user.business_id');

        $settings = MpesaSetting::where('business_id', $business_id)->first();

        if (!$settings) {
            $settings = new MpesaSetting();
            $settings->business_id = $business_id;
            $settings->environment = 'sandbox';
            $settings->shortcode_type = 'paybill';
        }

        // URLs the admin needs to paste into the Daraja portal
        $stk_callback_url = action('\Modules\Mpesa\Http\Controllers\CallbackController@stkCallback', ['business_id' => $business_id]);
        $c2b_confirmation_url = action('\Modules\Mpesa\Http\Controllers\CallbackController@c2bConfirmation');
        $c2b_validation_url = action('\Modules\Mpesa\Http\Controllers\CallbackController@c2bValidation');

        return view('mpesa::settings.index', compact(
            'settings',
            'stk_callback_url',
            'c2b_confirmation_url',
            'c2b_validation_url'
        ));
    }

    /**
     * Save / update the settings entered by the admin.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('mpesa.settings')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $business_id = $request->session()->get('user.business_id');

            $request->validate([
                'environment' => 'required|in:sandbox,production',
                'shortcode' => 'required|string',
                'shortcode_type' => 'required|in:paybill,till',
                'consumer_key' => 'required|string',
                'consumer_secret' => 'nullable|string',
                'passkey' => 'nullable|string',
                'account_reference' => 'nullable|string|max:50',
                'transaction_desc' => 'nullable|string|max:50',
            ]);

            $settings = MpesaSetting::firstOrNew(['business_id' => $business_id]);

            // On first-time setup these are required, since there's no existing value to fall back on
            if (!$settings->exists) {
                $request->validate([
                    'consumer_secret' => 'required|string',
                    'passkey' => 'required|string',
                ]);
            }

            $settings->business_id = $business_id;
            $settings->environment = $request->input('environment');
            $settings->shortcode = $request->input('shortcode');
            $settings->shortcode_type = $request->input('shortcode_type');
            $settings->consumer_key = $request->input('consumer_key');

            // Only overwrite secret/passkey if the admin actually typed something new
            if ($request->filled('consumer_secret')) {
                $settings->consumer_secret = $request->input('consumer_secret');
            }
            if ($request->filled('passkey')) {
                $settings->passkey = $request->input('passkey');
            }

            $settings->account_reference = $request->input('account_reference');
            $settings->transaction_desc = $request->input('transaction_desc');
            $settings->is_enabled = $request->boolean('is_enabled');

            $settings->save();

            // Auto-label the "Custom Payment 1" slot as "M-Pesa" so it shows up
            // correctly everywhere in core UltimatePOS (POS screen, payment rows,
            // sale list filters, reports) without the admin doing this manually.
            if ($settings->is_enabled) {
                $this->ensureCustomPaymentLabel($business_id);
            }

            $output = [
                'success' => true,
                'msg' => __('mpesa::lang.settings_saved_successfully'),
            ];
        } catch (\Exception $e) {
            $output = [
                'success' => false,
                'msg' => $e->getMessage(),
            ];
        }

        if ($request->expectsJson()) {
            return $output;
        }

        $request->session()->flash($output['success'] ? 'success' : 'error', $output['msg']);

        return redirect()->action('\Modules\Mpesa\Http\Controllers\SettingController@index');
    }

    /**
     * Register the C2B Confirmation/Validation URLs with Safaricom for this shortcode.
     * Only relevant for businesses that take direct Paybill payments (not just STK push).
     */
    public function registerC2BUrls(Request $request)
    {
        if (!auth()->user()->can('mpesa.settings')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $business_id = $request->session()->get('user.business_id');

            $service = DarajaService::forBusiness($business_id);

            $confirmation_url = action('\Modules\Mpesa\Http\Controllers\CallbackController@c2bConfirmation');
            $validation_url = action('\Modules\Mpesa\Http\Controllers\CallbackController@c2bValidation');

            $service->registerC2BUrls($confirmation_url, $validation_url);

            $settings = MpesaSetting::where('business_id', $business_id)->first();
            $settings->c2b_registered = true;
            $settings->save();

            $output = [
                'success' => true,
                'msg' => __('mpesa::lang.c2b_urls_registered'),
            ];
        } catch (\Exception $e) {
            $output = [
                'success' => false,
                'msg' => $e->getMessage(),
            ];
        }

        return $output;
    }

    /**
     * Set this business's "Custom Payment 1" label to "M-Pesa" (only if it hasn't
     * already been customized to something else by the admin). This is what makes
     * the payment method show up as "M-Pesa" instead of "Custom Payment 1" in the
     * payment row dropdown, sale list, and reports throughout core UltimatePOS.
     */
    protected function ensureCustomPaymentLabel(int $business_id)
    {
        $business = \App\Business::find($business_id);

        if (!$business) {
            return;
        }

        $custom_labels = !empty($business->custom_labels) ? json_decode($business->custom_labels, true) : [];

        $current = $custom_labels['payments']['custom_pay_1'] ?? null;

        // Only set it if it's empty or was previously set by this module,
        // so we never clobber a label the admin deliberately chose.
        if (empty($current) || $current === 'M-Pesa') {
            $custom_labels['payments']['custom_pay_1'] = 'M-Pesa';
            $business->custom_labels = json_encode($custom_labels);
            $business->save();
        }
    }
}
