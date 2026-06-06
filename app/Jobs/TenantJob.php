<?php
namespace App\Jobs;

use App\Services\TenantContext;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

abstract class TenantJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Create a new job instance.
     */
    public array $tenant_db_config;

    public function __construct()
    {
        //
        $context = app(TenantContext::class)->get();
        if ($context) {
            $this->tenant_db_config = [
                'id'       => $context->id,
                'database' => $context->db_name,
            ];

        }
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}
