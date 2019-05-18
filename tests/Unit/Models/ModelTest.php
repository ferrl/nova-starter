<?php

namespace Tests\Unit\Models;

use Mockery;
use ReflectionClass;
use Tests\Support\ExampleModel;
use Tests\TestCase;

class ModelTest extends TestCase
{
    public function testItGeneratesUniqueIdentifierWhenCreating()
    {
        $dispatcher = $this->bindEventDispatcher();
        ExampleModel::setEventDispatcher($dispatcher);

        $model = new ExampleModel;

        $this->assertNull($model->getKey());

        $dispatcher->dispatch('eloquent.creating: '.get_class($model), $model);

        $this->assertNotNull($model->getKey());
    }

    public function testItWorksWithCustomGetterTypeCasting()
    {
        $typeCaster = Mockery::mock('TypeCaster');
        $this->bindTypeCaster($typeCaster);
        $model = new ExampleModel(['price' => 10000]);

        $typeCaster->shouldReceive('splitTypeAndOptions')
            ->with('money:R$')->andReturn(['money', ['R$']]);
        $typeCaster->shouldReceive('hasCaster')
            ->with('money')->andReturnTrue();
        $typeCaster->shouldReceive('getAttribute')
            ->with('money', 10000, ['R$'])->andReturn('R$ 100,00');

        $reflection = new ReflectionClass($model);
        $property = $reflection->getProperty('casts');
        $property->setAccessible(true);
        $property->setValue($model, ['price' => 'money:R$']);

        $this->assertEquals('R$ 100,00', $model->getAttributeValue('price'));
    }

    public function testItWorksWithCustomSetterTypeCasting()
    {
        $typeCaster = Mockery::mock('TypeCaster');
        $this->bindTypeCaster($typeCaster);
        $model = new ExampleModel;

        $typeCaster->shouldReceive('splitTypeAndOptions')
            ->with('money:R$')->andReturn(['money', ['R$']]);
        $typeCaster->shouldReceive('hasCaster')
            ->with('money')->andReturnTrue();
        $typeCaster->shouldReceive('setAttribute')
            ->with('money', $model, 'price', 'R$ 100,00', ['R$'])->andReturnNull();

        $reflection = new ReflectionClass($model);
        $property = $reflection->getProperty('casts');
        $property->setAccessible(true);
        $property->setValue($model, ['price' => 'money:R$']);

        $model->setAttribute('price', 'R$ 100,00');
    }
}
