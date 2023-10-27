<?php

use App\Livewire\Admin\Dashboard;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, get};

it('renders successfully', function () {
    Livewire::test(Dashboard::class)
        ->assertStatus(200);
});

it('should block the access to ab admin page if the user does not have the permission be an admin', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(Dashboard::class)
        ->assertForbidden();

    get(route('admin.dashboard'))
        ->assertForbidden();

});
