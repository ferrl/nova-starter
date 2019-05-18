<?php

namespace Tests\Support\Helpers;

use App\Models\Model;
use Illuminate\Foundation\Application;

/**
 * @property-read Application $app
 */
trait MakesBindings
{
    /**
     * Bind event dispatcher to application.
     *
     * @param \Illuminate\Contracts\Events\Dispatcher $dispatcher
     * @return \Illuminate\Contracts\Events\Dispatcher
     */
    public function bindEventDispatcher($dispatcher = null)
    {
        $dispatcher = $dispatcher ?: new \Illuminate\Events\Dispatcher;

        $this->app->bind('Illuminate\Contracts\Events\Dispatcher', function () use ($dispatcher) {
            return $dispatcher;
        });

        Model::setEventDispatcher($dispatcher);

        return $dispatcher;
    }

    /**
     * Bind event dispatcher to application.
     *
     * @param \App\Domain\Support\TypeCaster $typeCaster
     * @return \App\Domain\Support\TypeCaster
     */
    public function bindTypeCaster($typeCaster = null)
    {
        $typeCaster = $typeCaster ?: new \App\Domain\Support\TypeCaster;

        $this->app->bind('TypeCaster', function () use ($typeCaster) {
            return $typeCaster;
        });

        Model::setTypeCaster($typeCaster);

        return $typeCaster;
    }
}
