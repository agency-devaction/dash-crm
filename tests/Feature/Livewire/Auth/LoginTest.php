<?php

use App\Livewire\Auth\Login;
use App\Models\User;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Login::class)
        ->assertOk();
});

it('should be able to login', function () {
    $user = User::factory()->create([
        'email'    => 'joe@gmail.com',
        'password' => 'password',
    ]);
    Livewire::test(Login::class)
        ->set('email', 'joe@gmail.com')
        ->set('password', 'password')
        ->call('tryToLogin')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));

    expect(auth()->check())
        ->toBeTrue()
    ->and(auth()->user())->id->toBe($user->id);
});

it('should make sure to inform the user if the credentials are invalid', function () {
    Livewire::test(Login::class)
        ->set('email', 'joe@gmail.com')
        ->set('password', 'password')
        ->call('tryToLogin')
        ->assertHasErrors(['invalidCredentials'])
    ->assertSee(trans('auth.failed'));

});
