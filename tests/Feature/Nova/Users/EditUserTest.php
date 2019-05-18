<?php

namespace Tests\Feature\Nova\Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditUserTest extends TestCase
{
    use RefreshDatabase;

    public function testUpdatesUserWithValidAttributes()
    {
        $user = factory(User::class)->create();
        $validParams = factory(User::class)->make();

        $previousPassword = $user->getAttribute('password');

        $this->actingAs($user)
            ->putJson('nova-api/users/' . $user->getKey(), [
                'name' => $validParams->getAttribute('name'),
                'email' => $validParams->getAttribute('email'),
                'password' => 'password',
                'password_confirmation' => 'password',
            ])
            ->assertJsonFragment(['name' => $validParams->getAttribute('name')])
            ->assertJsonFragment(['email' => $validParams->getAttribute('email')])
            ->assertJsonMissingExact(['password' => $previousPassword]);
    }

    public function testEmailMustBeUnique()
    {
        $user = factory(User::class)->create();
        $concurrentUser = factory(User::class)->create();

        $this->actingAs($user)
            ->putJson('nova-api/users/' . $user->getKey(), [
                'name' => $user->getAttribute('name'),
                'email' => $concurrentUser->getAttribute('email'),
            ])
            ->assertJsonValidationErrors('email');
    }
}
