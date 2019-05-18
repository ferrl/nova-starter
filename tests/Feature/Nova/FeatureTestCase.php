<?php

namespace Tests\Feature\Nova;

use App\Domain\Support\PermissionList;
use Tests\TestCase;

abstract class FeatureTestCase extends TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        PermissionList::createMissingPermissions();
    }
}
