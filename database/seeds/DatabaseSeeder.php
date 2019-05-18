<?php

use App\Domain\Support\ACL\PermissionList;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();

        $permissions = PermissionList::createMissingPermissions();
        $user = $this->createUser();
        $role = $this->createRole();

        $role->givePermissionTo($permissions);
        $user->assignRole($role);
    }

    /**
     * Create default administrator user.
     *
     * @return User
     */
    private function createUser()
    {
        $user = factory(User::class)->create([
            'email' => 'admin@domain.tld',
            'name' => 'Administrator'
        ]);

        $user->update(['id' => 'fe767460-87ff-4f5e-be7e-5c2cdfe679a8']);

        return $user;
    }

    /**
     * Create default administrator role.
     *
     * @return Role
     */
    private function createRole()
    {
        return factory(Role::class)
            ->state('web')
            ->create(['name' => 'Account Manager']);
    }
}
