<?php
namespace App\Http\Middleware;

use App\Services\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Models\Tenant;
use Symfony\Component\HttpFoundation\Response;

class TenantSwitcher
{
    protected TenantContext $context;

    public function __construct(TenantContext $context)
    {
        $this->context = $context;
    }
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $code = $request->header('X-Tenant-Code');

        // 2. Lookup tenant configurations in the central/shared database connection
        $tenant = Tenant::where('code', $code)->first();

        if (! $tenant) {
            abort(404, 'Tenant not found.');
        }

        // 3. Inject new runtime database config credentials dynamically
        Config::set('database.connections.tenant.database', $tenant->db_name);

        // 4. Purge internal connection cache and reconnect
        DB::purge('tenant');
        DB::reconnect('tenant');

        // 5. Set as default connection so Eloquent defaults to this schema
        DB::setDefaultConnection('tenant');

        // Isolate secondary shared platform drivers
        Config::set('cache.prefix', 'tenant_' . $tenant->id . '_');

        $this->context->set($tenant);
        return $next($request);
    }
}
