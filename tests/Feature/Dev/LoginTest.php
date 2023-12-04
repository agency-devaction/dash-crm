<?php

use App\Livewire\Dev;
use App\Models\User;

it('should be able to list all users of the system', function () {
    User::factory()->count(10)->create();

    $user = User::all();

    Livewire::test(Dev\Login::class)
        ->assertSet('users', $user)
        ->assertSee($user->first()->name);
});
