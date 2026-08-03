<?php

namespace Modules\Mpesa\Http\Controllers;

use App\System;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class InstallController extends Controller
{
    protected $module_name;

    protected $module_display_name;

    protected $appVersion;

    public function __construct()
    {
        $this->module_name = 'mpesa';
        $this->appVersion = config('mpesa.module_version');
        $this->module_display_name = 'M-Pesa';
    }

    /**
     * Install - shows the same install form used by other modules
     * (e.g. WooCommerce), via the core "install.install-module" view.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('superadmin')) {
            abort(403, 'Unauthorized action.');
        }

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '512M');

        $this->installSettings();

        // Check if already installed
        $is_installed = System::getProperty($this->module_name . '_version');
        if (!empty($is_installed)) {
            abort(404);
        }

        $action_url = action([\Modules\Mpesa\Http\Controllers\InstallController::class, 'install']);

        $intruction_type = 'cc';
        $action_type = 'install';
        $module_display_name = $this->module_display_name;

        return view('install.install-module')
            ->with(compact('action_url', 'intruction_type', 'action_type', 'module_display_name'));
    }

    /**
     * Installing M-Pesa Module.
     *
     * NOTE: The license_code / login_username fields are accepted (since the
     * shared "install.install-module" view requires them to be filled in to
     * submit the form) but are NOT validated against any license server -
     * this module is free / custom-built, so any value works.
     */
    public function install()
    {
        try {
            DB::beginTransaction();

            // Already installed?
            $is_installed = System::getProperty($this->module_name . '_version');
            if (!empty($is_installed)) {
                abort(404);
            }

            DB::statement('SET default_storage_engine=INNODB;');
            Artisan::call('module:migrate', ['module' => 'Mpesa', '--force' => true]);
            System::addProperty($this->module_name . '_version', $this->appVersion ?: '1.0.0');

            DB::commit();

            $output = [
                'success' => 1,
                'msg' => 'M-Pesa module installed successfully',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency('File:' . $e->getFile() . ' Line:' . $e->getLine() . ' Message:' . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => $e->getMessage(),
            ];
        }

        return redirect()
            ->action([\App\Http\Controllers\Install\ModulesController::class, 'index'])
            ->with('status', $output);
    }

    /**
     * Initialize install functions.
     */
    private function installSettings()
    {
        config(['app.debug' => true]);
        Artisan::call('config:clear');
    }

    /**
     * Update - run pending migrations if appVersion > installed version.
     */
    public function update()
    {
        if (!auth()->user()->can('superadmin')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();

            ini_set('max_execution_time', 0);
            ini_set('memory_limit', '512M');

            $installed_version = System::getProperty($this->module_name . '_version');

            if (empty($installed_version) || \Composer\Semver\Comparator::greaterThan($this->appVersion, $installed_version)) {
                $this->installSettings();

                DB::statement('SET default_storage_engine=INNODB;');
                Artisan::call('module:migrate', ['module' => 'Mpesa', '--force' => true]);

                System::setProperty($this->module_name . '_version', $this->appVersion ?: '1.0.0');
            } else {
                abort(404);
            }

            DB::commit();

            $output = [
                'success' => 1,
                'msg' => 'M-Pesa module updated successfully to version ' . $this->appVersion . ' !!',
            ];

            return redirect()
                ->action([\App\Http\Controllers\HomeController::class, 'index'])
                ->with('status', $output);
        } catch (\Exception $e) {
            DB::rollBack();
            exit($e->getMessage());
        }
    }

    /**
     * Uninstall - removes the version flag so the module shows as
     * "not installed" again. Does not drop tables (data is preserved).
     */
    public function uninstall()
    {
        if (!auth()->user()->can('superadmin')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            System::removeProperty($this->module_name . '_version');

            $output = [
                'success' => true,
                'msg' => __('lang_v1.success'),
            ];
        } catch (\Exception $e) {
            $output = [
                'success' => false,
                'msg' => $e->getMessage(),
            ];
        }

        return redirect()->back()->with(['status' => $output]);
    }
}
