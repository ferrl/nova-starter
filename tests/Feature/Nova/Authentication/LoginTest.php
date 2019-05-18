<?php

namespace Tests\Feature\Nova\Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Nova\FeatureTestCase;

class LoginTest extends FeatureTestCase
{
    use RefreshDatabase;

    public function testCanLoginWithValidUser()
    {
        $user = factory(User::class)->state('root')->create();

        $this->post('admin/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertSessionHasNoErrors();
    }

    public function testReceiveErrorIfPasswordIsInvalid()
    {
        $user = factory(User::class)->state('root')->create();

        $this->post('admin/login', [
            'email' => $user->email,
            'password' => 'incorrect',
        ])->assertSessionHasErrors('email');
    }

    public function testAllFieldsAreRequired()
    {
        $this->post('admin/login')->assertSessionHasErrors(['email', 'password']);
    }
}
