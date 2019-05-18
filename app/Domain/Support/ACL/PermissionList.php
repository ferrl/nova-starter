<?php

namespace App\Domain\Support\ACL;

use App\Models\Permission as Model;
use Illuminate\Support\Collection;
use Spatie\Permission\PermissionRegistrar;

class PermissionList
{
    /**
     * Get list of permissions.
     *
     * @return Collection|Permission[]
     */
    public static function getList()
    {
        return collect([
            new Permission('control_panel_access', 'Control Panel Access', 'General'),
            new Permission('manage_users', 'Manage Users', 'ACL'),
            new Permission('manage_roles', 'Manage Roles', 'ACL'),
            new Permission('manage_permissions', 'Manage Permissions', 'ACL'),
        ]);
    }

    /**
     * Create missing permissions from database.
     *
     * @return Permission[]
     */
    public static function createMissingPermissions()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = static::getList()->map(function (Permission $permission) {
            return Model::findOrCreate($permission->name, 'web');
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return $permissions;
    }

    /**
     * Get permission display name.
     *
     * @param string $name
     * @return string
     */
    public static function getDisplayName($name)
    {
        return static::getList()->keyBy('name')->get($name, new Permission)->displayName;
    }
}
