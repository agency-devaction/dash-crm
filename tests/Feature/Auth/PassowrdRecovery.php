<?php

use App\Livewire\Auth\Password\Recovery;
use App\Models\User;
use App\Notifications\Auth\PasswordRecoverNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\{get};

test('needs to have a route to password recovery', function () {

    get(route('password.recovery'))
        ->assertSeeLivewire('auth.password.recovery')
        ->assertOk();
});

it('should be able to request for a password recovery sending notification to user', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(Recovery::class)
        ->set('email', $user->email)
        ->call('startPasswordRecovery')
        ->assertSee(__('You will receive an email with a link to reset your password.'));

    Notification::assertSentTo($user, PasswordRecoverNotification::class);
});

test('making sure the email is a real email property', function (string $value, string $rule) {

    Livewire::test(Recovery::class)
        ->set('email', $value)
        ->call('startPasswordRecovery')
        ->assertHasErrors(['email' => $rule]);
})->with([
    'required' => ['value' => '', 'rule' => 'required'],
    'email'    => ['value' => 'not-a-valid-email', 'rule' => 'email'],
]);
