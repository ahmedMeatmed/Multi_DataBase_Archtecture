<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Admin\Database\Factories\TenantFactory;

class Tenant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $connection = 'mysql';
    protected $fillable = [];

    // protected static function newFactory(): TenantFactory
    // {
    //     // return TenantFactory::new();
    // }
}
