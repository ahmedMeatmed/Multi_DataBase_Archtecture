<?php
namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Models\Tenant;

#[Signature('app:tenant-migrate-command')]
#[Description('Command description')]
class TenantMigrateCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $tenantId = $this->option('tenant');
        $code     = 'code';
        $tenant   = Tenant::where('code', $code)->first();

        if (! $tenant) {
            $this->error('No tenant database connection targets found.');
            return Command::FAILURE;
        }

        Config::set('database.connections.tenant.database', $tenant->db_name);

        DB::purge('tenant');
        Artisan::call('migrate');

        $this->line(Artisan::output());

        $this->info('Isolated Multi-Tenant Migrations Processing Phase Completed.');
        return Command::SUCCESS;
    }
}
