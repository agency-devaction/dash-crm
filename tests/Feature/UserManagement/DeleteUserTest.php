<?php

use App\Livewire\Admin;
use App\Models\User;

use function Pest\Laravel\{actingAs, assertNotSoftDeleted, assertSoftDeleted};

it('should be able to delete user', function () {
    $user = User::factory()->admin()->create();
    actingAs($user);

    $forDeletion = User::factory()->create();

    Livewire::test(Admin\Users\Delete::class, ['user' => $forDeletion])
        ->set('confirmation_confirmation', 'OK I AM SURE')
        ->call('destroy')
        ->assertDispatched('user::deleted')
        ->assertHasNoErrors();

    assertSoftDeleted('users', ['id' => $forDeletion->id]);
});

it('should have a confirmation before deletion', function () {
    $user        = User::factory()->admin()->create();
    $forDeletion = User::factory()->create();

    actingAs($user);
    Livewire::test(Admin\Users\Delete::class, ['user' => $forDeletion])
        ->call('destroy')
        ->assertHasErrors(['confirmation' => 'confirmed'])
        ->assertNotDispatched('user::deleted');

    assertNotSoftDeleted('users', ['id' => $forDeletion->id]);
});
