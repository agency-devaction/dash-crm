<?php

use App\Livewire\Auth\Password;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Livewire\Livewire;

use function Pest\Laravel\get;

test('need to receive token with a combination with the e-mail', function () {
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
