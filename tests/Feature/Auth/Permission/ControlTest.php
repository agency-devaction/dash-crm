<?php

use App\Models\User;

use function Pest\Laravel\assertDatabaseHas;

it('should be able to give a user a permission to something', function () {
    $user = User::factory()->create();

    $user->givePermissionTo('be an admin');

    expect($user->hasPermissionTo('be an admin'))->toBeTrue();

    assertDatabaseHas('permissions', [
        'name' => 'be an admin',
    ]);

});
