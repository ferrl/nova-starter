<?php

namespace App\Domain\Support;

use App\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionList
{
    /**
     * Get list of permissions.
     *
     * @return array
     */
    public static function getList()
    {
        return [
            'control_panel_access' => ['description' => 'Control Panel Access', 'group' => 'General'],
            'manage_users' => ['description' => 'Manage Users', 'group' => 'ACL'],
            'manage_roles' => ['description' => 'Manage Roles', 'group' => 'ACL'],
            'manage_permissions' => ['description' => 'Manage Permissions', 'group' => 'ACL'],
        ];
    }

    /**
     * Create missing permissions from database.
     *
     * @return Permission[]
     */
    public static function createMissingPermissions()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = collect(static::getList())->map(function ($label, $name) {
            return Permission::findOrCreate($name, 'web');
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return $permissions;
    }

    /**
     * Get permission description.
     *
     * @param string $name
     * @return string
     */
    public static function getDescription($name)
    {
        return data_get(static::getList(), $name . '.description');
    }
}
