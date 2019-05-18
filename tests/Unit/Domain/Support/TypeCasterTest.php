<?php

namespace Tests\Unit\Domain\Support;

use App\Domain\Support\TypeCaster;
use Mockery;
use Tests\TestCase;

class TypeCasterTest extends TestCase
{
    public function testRegistersCastingWithoutParams()
    {
        $typeCaster = new TypeCaster;
        $typeCaster->extend('money');

        $this->assertTrue($typeCaster->hasCaster('money'));
        $this->assertFalse($typeCaster->hasCaster('document'));
    }

    public function testGetAttributeWithCastingMethod()
    {
        $typeCaster = new TypeCaster;
        $typeCaster->extend('money', function ($value, $options) {
            return $value . ':' . implode(',', $options);
        });

        $actual = $typeCaster->getAttribute('money', 100, ['R$']);
        $this->assertEquals('100:R$', $actual);
    }

    /** @expectedException \ErrorException */
    public function testFailsWhenCastingDoesntExist()
    {
        $typeCaster = new TypeCaster;

        $typeCaster->getAttribute('money', 100, ['R$']);
    }

    public function testSetValueUsingCastingMethod()
    {
        $typeCaster = new TypeCaster;
        $typeCaster->extend('money', null, function ($model, $attribute, $value, $options) {
            $model->setAttribute($attribute, preg_replace('/[^0-9]/', '', $value));
        });

        $model = Mockery::mock('Model')
            ->shouldReceive('setAttribute')
            ->with('price', '10000')
            ->getMock();

        $typeCaster->setAttribute('money', $model, 'price', 'R$100,00', []);
    }

    public function testSplitStringWithNoOptions()
    {
        $typeCaster = new TypeCaster;
        $castString = 'money';

        $this->assertEquals(['money', []], $typeCaster->splitTypeAndOptions($castString));
    }

    public function testSplitStringWithOneOption()
    {
        $typeCaster = new TypeCaster;
        $castString = 'money:R$';

        $this->assertEquals(['money', ['R$']], $typeCaster->splitTypeAndOptions($castString));
    }

    public function testSplitStringWithMultipleOptions()
    {
        $typeCaster = new TypeCaster;
        $castString = 'money:R$,comma';

        $this->assertEquals(['money', ['R$', 'comma']], $typeCaster->splitTypeAndOptions($castString));
    }
}
