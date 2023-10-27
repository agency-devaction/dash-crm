<?php

use App\Enum\Can;
use App\Models\{Permission, User};
use Database\Seeders\{PermissionSeeder, UsersSeeder};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\{Cache, DB};

use function Pest\Laravel\{actingAs, assertDatabaseHas, seed};

it('should be able to give a user a permission to something', function () {
    $user = User::factory()->create();

    $user->givePermissionTo(Can::BE_AN_ADMIN);

    expect($user->hasPermissionTo(Can::BE_AN_ADMIN))->toBeTrue();

    assertDatabaseHas('permissions', [
        'key' => Can::BE_AN_ADMIN->value,
    ]);

    assertDatabaseHas('permission_user', [
        'user_id'       => $user->id,
        'permission_id' => Permission::query()->where('key', '=', Can::BE_AN_ADMIN)->first()?->getKey(),
    ]);

});

test('permission has to have a seeder', function () {
    seed([PermissionSeeder::class, UsersSeeder::class]);

    assertDatabaseHas('permissions', [
        'key' => Can::BE_AN_ADMIN,
    ]);

    assertDatabaseHas('permission_user', [
        'user_id'       => User::first()?->getKey(),
        'permission_id' => Permission::query()->where('key', '=', Can::BE_AN_ADMIN->value)->first()?->getKey(),
    ]);
});

it('should block the access to ab admin page if the user does not have the permission be an admin', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertStatus(403);
});

test("Let's make sure that we are using cache to store user permission", function () {
    $user = User::factory()->create();

    $user->givePermissionTo(Can::BE_AN_ADMIN);

    $cacheKey = "user::{$user->id}::permissions";

    expect(Cache::has($cacheKey))->toBeTrue('Checking if the cache key exists')
        ->and(Cache::get($cacheKey))->toBe($user->permissions, 'Checking if the cache key has the correct value');

});

test("let's make sure that we are using the cache the retrieve/check when the user has the given permission", function () {
    $user = User::factory()->create();

    $user->givePermissionTo(Can::BE_AN_ADMIN);

    DB::listen(fn (Builder $query) => throw new RuntimeException('We got a hit on the database'));

    $user->hasPermissionTo(Can::BE_AN_ADMIN);

    expect(true)->toBeTrue();

});
