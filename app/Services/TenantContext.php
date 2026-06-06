<?php
namespace App\Services;

use stdClass;

class TenantContext
{
    protected ?stdClass $currentTenant = null;

    public function set(stdClass $tenant): void
    {
        $this->currentTenant = $tenant;
    }

    public function get(): ?stdClass
    {
        return $this->currentTenant;
    }

    public function forget(): void
    {
        $this->currentTenant = null;
    }
}
