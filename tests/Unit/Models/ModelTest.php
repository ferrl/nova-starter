<?php

namespace Tests\Unit\Models;

use Tests\Support\ExampleModel;
use Tests\TestCase;

class ModelTest extends TestCase
{
    public function testItGeneratesUniqueIdentifierWhenCreating()
    {
        $model = new ExampleModel;

        $this->assertNull($model->getKey());

        app('Illuminate\Contracts\Events\Dispatcher')->dispatch(
            'eloquent.creating: '.get_class($model), $model
        );

        $this->assertNotNull($model->getKey());
    }
}
