<?php

namespace App\Nova\Resources;

use App\Nova\Actions\AttachMultiplePermissions;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Vyuldashev\NovaPermission\Role as Resource;

class Role extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Models\\Role';

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        $guardOptions = collect(config('auth.guards'))->mapWithKeys(function ($value, $key) {
            return [$key => $key];
        });

        return [
            ID::make()->onlyOnDetail(),

            Text::make(__('nova-permission-tool::roles.name'), 'name')
                ->rules(['required', 'string', 'max:255'])
                ->creationRules('unique:'.config('permission.table_names.roles'))
                ->updateRules('unique:'.config('permission.table_names.roles').',name,{{resourceId}}'),

            Select::make(__('nova-permission-tool::roles.guard_name'), 'guard_name')
                ->options($guardOptions->toArray())
                ->rules(['required', Rule::in($guardOptions)]),

            DateTime::make(__('nova-permission-tool::roles.created_at'), 'created_at')->exceptOnForms(),

            DateTime::make(__('nova-permission-tool::roles.updated_at'), 'updated_at')->exceptOnForms(),

            BelongsToMany::make(Permission::label(), 'permissions', Permission::class)
                ->searchable()
                ->singularLabel(Permission::singularLabel()),
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            (new AttachMultiplePermissions)
                ->canSee(function (NovaRequest $request) {
                    return $request->user()->can('manage_roles');
                })
                ->canRun(function (NovaRequest $request, $model) {
                    return $request->user()->can('manage_roles');
                }),
        ];
    }
}
