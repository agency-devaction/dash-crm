<?php

use App\Livewire\Admin;
use App\Models\User;
use App\Notifications\AccountRestored;

use function Pest\Laravel\{actingAs, assertNotSoftDeleted, assertSoftDeleted};

it('should be able to restore user', function () {
    $user = User::factory()->admin()->create();
    actingAs($user);

    $forRestoring = User::factory()->deleted()->create();

    Livewire::test(Admin\Users\Restore::class, ['user' => $forRestoring])
        ->set('confirmation_confirmation', 'OK I AM SURE')
        ->call('restore')
        ->assertDispatched('user::restored')
        ->assertHasNoErrors();

    assertNotSoftDeleted('users', ['id' => $forRestoring->id]);

    $forRestoring->refresh();

    expect($forRestoring)
        ->restored_by
        ->not
        ->toBeNull()
        ->restoredBy
        ->id
        ->toBe($user->id);
});

it('should have a confirmation before restore', function () {
    $user         = User::factory()->admin()->create();
    $forRestoring = User::factory()->deleted()->create();

    actingAs($user);
    Livewire::test(Admin\Users\Restore::class, ['user' => $forRestoring])
        ->call('restore')
        ->assertHasErrors(['confirmation' => 'confirmed'])
        ->assertNotDispatched('user::restored');

    assertSoftDeleted('users', ['id' => $forRestoring->id]);
});

it('should send a notification to the user again  access to the application', function () {
    Notification::fake();

    $user = User::factory()->admin()->create();
    actingAs($user);

    $forRestoring = User::factory()->deleted()->create();

    Livewire::test(Admin\Users\Restore::class, ['user' => $forRestoring])
        ->set('confirmation_confirmation', 'OK I AM SURE')
        ->call('restore');

    Notification::assertSentTo($forRestoring, AccountRestored::class);

});
