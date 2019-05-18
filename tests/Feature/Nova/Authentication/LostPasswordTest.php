<?php

namespace Tests\Feature\Nova\Authentication;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class LostPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function testReceiveEmailWhenEmailExists()
    {
        Notification::fake();
        $user = factory(User::class)->create();

        $this->post('admin/password/email', [
            'email' => $user->email,
        ])->assertSessionHasNoErrors();

        Notification::assertSentTo($user, ResetPassword::class, 1);
    }

    public function testDoesNotReceiveEmail()
    {
        Notification::fake();

        $this->post('admin/password/email', [
            'email' => 'inexistent@example.org',
        ])->assertSessionHasErrors('email');

        Notification::assertNothingSent();
    }
}
