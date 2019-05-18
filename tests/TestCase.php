<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Support\Helpers\MakesBindings;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, MakesBindings;
}
