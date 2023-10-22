<?php

use App\Livewire\Auth\Password;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Livewire\Livewire;

use function Pest\Laravel\get;
use function PHPUnit\Framework\assertTrue;

test('need to receive token with a combination with the e-mail open the page', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(Password\Recovery::class)
        ->set('email', $user->email)
        ->call('startPasswordRecovery');

    Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) {
        get(route('password.reset', [
            'token' => $notification->token,
        ]))
            ->assertSuccessful();

        get(route('password.reset', [
            'token' => 'any-token',
        ]))
            ->assertRedirect('login');

        return true;
    });

});

test('if is possible to reset the password with the give token', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(Password\Recovery::class)
        ->set('email', $user->email)
        ->call('startPasswordRecovery');

    Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use ($user) {
        Livewire::test(Password\Reset::class, [
            'token' => $notification->token,
            'email' => $user->email,
        ])
            ->set('email_confirmation', $user->email)
            ->set('password', 'new-password')
            ->set('password_confirmation', 'new-password')
            ->call('updatePassword')
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard'));

        $user->refresh();

        assertTrue(Hash::check('new-password', $user->password));

        return true;
    });
});
