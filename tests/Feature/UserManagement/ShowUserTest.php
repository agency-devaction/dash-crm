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
        ->assertSee($userToShow->created_at->format('d/m/Y H:i'))
        ->assertSee($userToShow->updated_at->format('d/m/Y H:i'))
        ->assertSee($userToShow->deleted_at->format('d/m/Y H:i'))
        ->assertSee($userToShow->deletedBy->name);

});
