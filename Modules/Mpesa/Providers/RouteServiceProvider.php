<?php

namespace Modules\Mpesa\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     */
    protected $moduleNamespace = 'Modules\Mpesa\Http\Controllers';

    /**
     * Called before routes are registered.
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     */
    public function map()
    {
        $this->mapWebRoutes();
        $this->mapCallbackRoutes();
    }

    /**
     * Standard authenticated routes (settings, transactions, sell-screen AJAX).
     * Loaded inside the app's default "web" middleware group so auth/session/CSRF
     * and the AdminSidebarMenu builder all work as expected.
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->moduleNamespace)
            ->group(module_path('Mpesa', '/Routes/web.php'));
    }

    /**
     * Public Safaricom callback routes - intentionally NOT inside the "web"
     * middleware group so CSRF verification does not block Safaricom's POSTs.
     */
    protected function mapCallbackRoutes()
    {
        Route::namespace($this->moduleNamespace)
            ->group(module_path('Mpesa', '/Routes/api.php'));
    }
}
