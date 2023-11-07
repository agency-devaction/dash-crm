<?php

use App\Livewire\Admin;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('should be able show all the details of a user', function () {
    $admin      = User::factory()->admin()->create();
    $userToShow = User::factory()->deleted()->create();

    actingAs($admin);

    Livewire::test(Admin\Users\Show::class)
        ->call('loadUser', $userToShow->id)
        ->assertSet('user.id', $userToShow->id)
        ->assertSet('modal', true)
        ->assertSee($userToShow->name)
        ->assertSee($userToShow->email)
        ->assertSee(!is_null($userToShow->created_at) ? $userToShow->created_at->format('d/m/Y H:i') : '')
        ->assertSee(!is_null($userToShow->updated_at) ? $userToShow->updated_at->format('d/m/Y H:i') : '')
        ->assertSee(!is_null($userToShow->deleted_at) ? $userToShow->deleted_at->format('d/m/Y H:i') : '')
        ->assertSee($userToShow->deletedBy->name ?? '');

});

it('should open the modal when the event is dispatched', function () {
    $admin      = User::factory()->admin()->create();
    $userToShow = User::factory()->deleted()->create();

    actingAs($admin);

    Livewire::test(Admin\Users\Index::class)
        ->call('showUser', $userToShow->id)
        ->assertDispatched('user::show', id: $userToShow->id);
});

/** @throws ReflectionException */
test('making sure that method loadUser has the attribute On', function () {
    $livewireClass = new  Admin\Users\Show();

    try {
        $reflection = new ReflectionClass($livewireClass);
        $attributes = $reflection->getMethod('loadUser')->getAttributes(); // Make sure 'loadUser' is correct
    } catch (ReflectionException $e) {
        $this->fail('ReflectionException caught: ' . $e->getMessage());
    }

    expect($attributes)->toHaveCount(1);

    $argument = $attributes[0]->getArguments()[0];

    expect($argument)->toBe('user::show');
});
