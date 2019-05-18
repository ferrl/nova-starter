<?php

namespace Tests\Unit\Domain\Support;

use App\Domain\Support\UniversalIdentifierGenerator;
use Tests\TestCase;

class UniversalIdentifierGeneratorTest extends TestCase
{
    public function testGenerateValidUuid4()
    {
        $generator = new UniversalIdentifierGenerator;
        $pattern = '/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/';

        $this->assertRegExp($pattern, $generator->generate());
    }
}
