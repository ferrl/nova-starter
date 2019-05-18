<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the resource list.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->checkPermissionTo('manage_roles');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Role $model
     * @return mixed
     */
    public function view(User $user, Role $model)
    {
        return $user->checkPermissionTo('manage_roles');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->checkPermissionTo('manage_roles');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Role $model
     * @return mixed
     * @throws \Exception
     */
    public function update(User $user, Role $model)
    {
        return $user->checkPermissionTo('manage_roles') &&
            ! $model->isRootRole();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Role $model
     * @return mixed
     * @throws \Exception
     */
    public function delete(User $user, Role $model)
    {
        return $user->checkPermissionTo('manage_roles') &&
            ! $model->isRootRole();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Role $model
     * @return mixed
     * @throws \Exception
     */
    public function restore(User $user, Role $model)
    {
        return $user->checkPermissionTo('manage_roles') &&
            ! $model->isRootRole();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Role $model
     * @return mixed
     * @throws \Exception
     */
    public function forceDelete(User $user, Role $model)
    {
        return $user->checkPermissionTo('manage_roles') &&
            ! $model->isRootRole();
    }
}
