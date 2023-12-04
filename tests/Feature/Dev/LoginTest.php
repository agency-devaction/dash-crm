<?php

use App\Livewire\Dev;
use App\Models\User;

use function Pest\Laravel\assertAuthenticatedAs;

it('should be able to list all users of the system', function () {
    User::factory()->count(10)->create();

    $user = User::all();

    Livewire::test(Dev\Login::class)
        ->assertSet('users', $user)
        ->assertSee($user->first()->name);
});

it('should be to login with any user', function () {
    $user = User::factory()->create();

    Livewire::test(Dev\Login::class)
        ->set('selectedUser', $user->id)
        ->call('login')
        ->assertRedirect(route('welcome'));

    assertAuthenticatedAs($user);
});
