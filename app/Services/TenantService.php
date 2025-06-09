<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Tenant;
use Nette\Schema\ValidationException;

class TenantService
{
    private static $tenant;
    private static $domain;
    private static $db_name;

    public static function switchToTenant(Tenant $tenant)
    {
        if (!$tenant instanceof Tenant) {
            throw ValidationException::withMessages(['field_name' => 'this value is incorrect']);
        }

        DB::purge('system');
        DB::purge('tenant');
        
        config(['database.connections.tenant.database' => $tenant->db_name]);

        self::$tenant = $tenant;
        self::$domain = $tenant->domain;
        self::$db_name = $tenant->db_name;

        DB::connection('tenant')->reconnect();
        DB::setDefaultConnection('tenant');
    }

    public static function switchToDefault()
    {
        DB::purge('system');
        DB::purge('tenant');
        DB::connection('system')->reconnect();
        DB::setDefaultConnection('system');
    }

    public static function getTenant()
    {
        return self::$tenant;
    }
}
