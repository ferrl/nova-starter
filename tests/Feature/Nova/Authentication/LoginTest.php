<?php

namespace Tests\Feature\Nova\Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function testCanLoginWithValidUser()
    {
        $user = factory(User::class)->create();

        $this->post('admin/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertSessionHasNoErrors();
    }

    public function testReceiveErrorIfPasswordIsInvalid()
    {
        $user = factory(User::class)->create();

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
