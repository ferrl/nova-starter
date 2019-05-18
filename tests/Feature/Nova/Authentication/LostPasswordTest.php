<?php

namespace Tests\Feature\Nova\Authentication;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\Feature\Nova\FeatureTestCase;

class LostPasswordTest extends FeatureTestCase
{
    use RefreshDatabase;

    public function testReceiveEmailWhenEmailExists()
    {
        Notification::fake();
        $user = factory(User::class)->state('root')->create();

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
