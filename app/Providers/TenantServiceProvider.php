<?php
namespace App\Providers;

use App\Services\TenantContext;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;

class TenantServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(TenantContext::class, function () {
            return new TenantContext();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        // Intercept worker execution blocks right before handling a payload
        Queue::before(function (JobProcessing $event) {
            $payload = $event->job->payload();

            // Check if tenant metadata environment context array exists
            if (isset($payload['data']['tenant_db_config'])) {
                $config = $payload['data']['tenant_db_config'];

                Config::set('database.connections.tenant.database', $config['database']);
                

                DB::purge('tenant');
                DB::reconnect('tenant');
                DB::setDefaultConnection('tenant');

                Config::set('cache.prefix', 'tenant_' . $config['id'] . '_');
            }
        }
        );
    }
}
