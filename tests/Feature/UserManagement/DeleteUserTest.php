<?php

use App\Livewire\Admin;
use App\Models\User;
use App\Notifications\AccountDeleted;

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

    ds($forDeletion->refresh());

    expect($forDeletion)
        ->deletedBy
        ->id
        ->toBe($user->id);
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

it('should send a notification to the user telling him that he has no long access to the application', function () {
    Notification::fake();

    $user = User::factory()->admin()->create();
    actingAs($user);

    $forDeletion = User::factory()->create();

    Livewire::test(Admin\Users\Delete::class, ['user' => $forDeletion])
        ->set('confirmation_confirmation', 'OK I AM SURE')
        ->call('destroy');

    Notification::assertSentTo($forDeletion, AccountDeleted::class);

});

it('should not be possible to delete the logged user', function () {
    $user = User::factory()->admin()->create();
    actingAs($user);

    Livewire::test(Admin\Users\Delete::class, ['user' => $user])
        ->set('user', $user)
        ->set('confirmation_confirmation', 'OK I AM SURE')
        ->call('destroy')
        ->assertHasErrors(['confirmation'])
        ->assertNotDispatched('user::deleted');
});
