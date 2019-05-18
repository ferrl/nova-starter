<?php

namespace App\Nova\Actions;

use App\Domain\Support\ACL\Permission;
use App\Domain\Support\ACL\PermissionList;
use App\Models\Role;
use Fourstacks\NovaCheckboxes\Checkboxes;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Laravel\Nova\Fields\Heading;

class AttachMultiplePermissions extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Perform the action on the given models.
     *
     * @param \Laravel\Nova\Fields\ActionFields $fields
     * @param \Illuminate\Support\Collection $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $models->map(function (Role $role) use ($fields) {
            $role->givePermissionTo(Arr::flatten($fields->getAttributes()));
        });
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return PermissionList::getList()
            ->groupBy('group')
            ->map(function (Collection $group, $groupName) {
                return [
                    Heading::make("<strong>{$groupName}</strong>")->asHtml(),
                    Checkboxes::make('Permissions', 'permissions_' . Str::slug($groupName))
                        ->options($group->mapWithKeys(function (Permission $permission) {
                            return [$permission->name => $permission->displayName];
                        })),
                ];
            })
            ->flatten()
            ->toArray();
    }
}
