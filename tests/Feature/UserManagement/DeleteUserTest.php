<?php

use App\Livewire\Admin;
use App\Models\User;

use function Pest\Laravel\{actingAs, assertSoftDeleted};

it('should be able to delete user', function () {
    $user = User::factory()->admin()->create();
    actingAs($user);

    $forDeletion = User::factory()->create();

    Livewire::test(Admin\Users\Delete::class, ['user' => $forDeletion])
        ->call('destroy')
        ->assertDispatched('user::deleted')
        ->assertHasNoErrors();

    assertSoftDeleted('users', ['id' => $forDeletion->id]);
});
