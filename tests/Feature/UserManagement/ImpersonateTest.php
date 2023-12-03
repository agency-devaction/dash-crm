<?php

use App\Livewire\Admin\Users\Impersonate;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function PHPUnit\Framework\{assertSame, assertTrue};

it('should add a key impersonate to the session with the given user', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(Impersonate::class)
        ->call('impersonate', $user->id);

    assertTrue(session()->has('impersonate'));

    assertSame(session()->get('impersonate'), $user->id);
});
