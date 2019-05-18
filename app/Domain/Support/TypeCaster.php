<?php

namespace App\Domain\Support;

use Illuminate\Database\Eloquent\Model;

class TypeCaster
{
    /**
     * List of caster functions.
     *
     * @var array
     */
    protected $casters = [];

    /**
     * Extend new type caster.
     *
     * @param string $type
     * @param \Closure $getAttribute
     * @param \Closure $setAttribute
     * @return static
     */
    public function extend($type, $getAttribute = null, $setAttribute = null)
    {
        $getAttribute = $getAttribute ?: $this->defaultGetFunction();
        $setAttribute = $setAttribute ?: $this->defaultSetFunction();

        $this->casters[$type] = ['getter' => $getAttribute, 'setter' => $setAttribute];

        return $this;
    }

    /**
     * Split cast string into type and options.
     *
     * @param string $castString
     * @return array
     */
    public function splitTypeAndOptions($castString)
    {
        $parts = explode(':', $castString);
        $type = data_get($parts, 0);
        $optionsString = data_get($parts, 1);

        $options = $optionsString ? explode(',', $optionsString) : [];

        return [$type, $options];
    }

    /**
     * Get attribute via caster.
     *
     * @param string $type
     * @param mixed $value
     * @param array $options
     * @return mixed
     */
    public function getAttribute($type, $value, $options)
    {
        return $this->casters[$type]['getter']($value, $options);
    }

    /**
     * Set attribute via caster.
     *
     * @param string $type
     * @param Model $model
     * @param string $attribute
     * @param mixed $value
     * @param array $options
     * @return mixed
     */
    public function setAttribute($type, $model, $attribute, $value, $options)
    {
        return $this->casters[$type]['setter']($model, $attribute, $value, $options);
    }

    /**
     * Check if caster exists.
     *
     * @param string $type
     * @return array
     */
    public function hasCaster($type)
    {
        return array_key_exists($type, $this->casters);
    }

    /**
     * Default getter function when missing.
     *
     * @return \Closure
     */
    private function defaultGetFunction()
    {
        return function ($value, $options) {
            return $value;
        };
    }

    /**
     * Default setter function when missing.
     *
     * @return \Closure
     */
    private function defaultSetFunction()
    {
        return function (Model $model, $attribute, $value, $options) {
            return $model->setAttribute($attribute, $value);
        };
    }
}
