<?php

namespace Tests\Feature\Nova\Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Nova\FeatureTestCase;

class CreateUserTest extends FeatureTestCase
{
    use RefreshDatabase;

    public function testCreatesUserWithValidAttributes()
    {
        $user = factory(User::class)->state('root')->create();
        $validParams = factory(User::class)->make();

        $this->actingAs($user)
            ->postJson('nova-api/users', [
                'name' => $validParams->getAttribute('name'),
                'email' => $validParams->getAttribute('email'),
                'password' => 'password',
                'password_confirmation' => 'password',
            ])
            ->assertJsonStructure(['id', 'resource' => ['name', 'email']]);
    }

    public function testFieldsAreRequired()
    {
        $user = factory(User::class)->state('root')->create();

        $this->actingAs($user)
            ->postJson('nova-api/users')
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function testPasswordMustBeConfirmed()
    {
        $user = factory(User::class)->state('root')->create();
        $validParams = factory(User::class)->make();

        $this->actingAs($user)
            ->postJson('nova-api/users', [
                'name' => $validParams->getAttribute('name'),
                'email' => $validParams->getAttribute('email'),
                'password' => 'password',
                'password_confirmation' => 'anything',
            ])
            ->assertJsonValidationErrors(['password']);
    }
}
