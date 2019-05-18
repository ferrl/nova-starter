<?php

namespace Tests\Feature\Nova\Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatesUserWithValidAttributes()
    {
        $user = factory(User::class)->create();
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
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->postJson('nova-api/users')
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function testPasswordMustBeConfirmed()
    {
        $user = factory(User::class)->create();
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
