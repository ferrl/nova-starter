<?php

namespace App\Nova\Resources;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Vyuldashev\NovaPermission\Permission as Resource;

class Permission extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Models\\Permission';

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

            Text::make(__('nova-permission-tool::permissions.name'), 'name')
                ->rules('required', 'string', 'max:255')
                ->creationRules('unique:'.config('permission.table_names.permissions'))
                ->updateRules('unique:'.config('permission.table_names.permissions').',name,{{resourceId}}'),

            Text::make(__('nova-permission-tool::permissions.display_name'), function () {
                return __('nova-permission-tool::permissions.display_names.'.$this->name);
            })->canSee(function () {
                return is_array(__('nova-permission-tool::permissions.display_names'));
            }),

            Select::make(__('nova-permission-tool::permissions.guard_name'), 'guard_name')
                ->options($guardOptions->toArray())
                ->rules('required', Rule::in($guardOptions)),

            DateTime::make(__('nova-permission-tool::permissions.created_at'), 'created_at')->exceptOnForms(),

            DateTime::make(__('nova-permission-tool::permissions.updated_at'), 'updated_at')->exceptOnForms(),

            BelongsToMany::make(Role::label(), 'roles', Role::class)
                ->searchable()
                ->singularLabel(Role::singularLabel()),
        ];
    }
}
