<?php

namespace App\Models\Concerns;

use App\Domain\Support\TypeCaster;

trait HasTypeCaster
{
    /**
     * Custom type casting for models.
     *
     * @var \App\Domain\Support\TypeCaster
     */
    public static $typeCaster = null;

    /**
     * Set default type caster if registered.
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public static function bootHasTypeCaster()
    {
        if (app()->has('TypeCaster')) {
            static::setTypeCaster(app()->make('TypeCaster'));
        }
    }

    /**
     * Set custom type caster.
     *
     * @param TypeCaster $typeCaster
     * @return void
     */
    public static function setTypeCaster($typeCaster)
    {
        static::$typeCaster = $typeCaster;
    }

    /**
     * Cast an attribute to a native PHP type.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function castAttribute($key, $value)
    {
        if (static::$typeCaster && $this->hasCast($key)) {
            [$castType, $options] = static::$typeCaster->splitTypeAndOptions($this->getCasts()[$key]);

            if (static::$typeCaster->hasCaster($castType)) {
                return static::$typeCaster->getAttribute($castType, $value, $options);
            }
        }

        return parent::castAttribute($key, $value);
    }

    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    public function setAttribute($key, $value)
    {
        if (static::$typeCaster && $this->hasCast($key)) {
            [$castType, $options] = static::$typeCaster->splitTypeAndOptions($this->getCasts()[$key]);

            if (static::$typeCaster->hasCaster($castType)) {
                return static::$typeCaster->setAttribute($castType, $this, $key, $value, $options);
            }
        }

        return parent::setAttribute($key, $value);
    }
}
