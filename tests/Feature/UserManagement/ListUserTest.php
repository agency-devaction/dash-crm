<?php

use App\Enum\Can;
use App\Livewire\Admin\Users;
use App\Models\{Permission, User};
use Illuminate\Pagination\LengthAwarePaginator;

use function Pest\Laravel\{actingAs, get};

it('should be able to access the route admin/users', function () {
    actingAs(User::factory()->admin()->create());

    get(route('admin.users'))
        ->assertOk();
});

test('make sure that route us protected by the permission BE_AN_ADMIN', function () {
    actingAs(User::factory()->create());

    get(route('admin.users'))
        ->assertForbidden();
});

test("let's create a livewire component to list all users in the page", function () {
    actingAs(User::factory()->admin()->create());

    $users = User::factory()->count(10)->create();

    $lw = Livewire::test(Users\Index::class);
    $lw->assertSet('users', function ($users) {
        expect($users)
            ->toBeInstanceOf(LengthAwarePaginator::class)
            ->toHaveCount(11);

        return true;
    });

    foreach ($users as $user) {
        $lw->assertSee($user->name);
    }
});

test('check the table format', function () {
    actingAs(User::factory()->admin()->create());

    Livewire::test(Users\Index::class)
        ->assertSet(
            'headers',
            [
                ['key' => 'id', 'label' => '#'],
                ['key' => 'name', 'label' => 'Name'],
                ['key' => 'email', 'label' => 'Email'],
            ]
        );
});

test('should be able to filter by name and email', function () {
    $admin = User::factory()->admin()->create(['name' => 'Joe Doe', 'email' => 'admin@gmail.com']);
    $mario = User::factory()->admin()->create(['name' => 'Mario', 'email' => 'mario_guy@gmail.com']);

    actingAs($admin);
    Livewire::test(Users\Index::class)
        ->assertSet('users', function ($users) {
            expect($users)
                ->toHaveCount(2);

            return true;
        })
    ->set('search', 'Mario')
    ->assertSet('users', function ($users) {
        expect($users)
            ->toHaveCount(1)
            ->first()
            ->name
            ->toBe('Mario');

        return true;
    })
        ->set('search', 'mario_guy')
        ->assertSet('users', function ($users) {
            expect($users)
                ->toHaveCount(1)
                ->first()
                ->name
                ->toBe('Mario');

            return true;
        });
});

test('should be able to filter by permission key', function () {

    $nonAdmin   = User::factory()->admin()->create(['name' => 'Mario', 'email' => 'mario_guy@gmail.com']);
    $admin      = User::factory()->admin()->create(['name' => 'Joe Doe', 'email' => 'admin@gmail.com']);
    $permission = Permission::where('key', Can::BE_AN_ADMIN->value)->first();

    actingAs($admin);
    Livewire::test(Users\Index::class)
        ->assertSet('users', function ($users) {
            expect($users)
                ->toHaveCount(2);

            return true;
        })
        ->set('search_permission', [$permission?->id])
        ->assertSet('users', function ($users) {
            expect($users)
                ->toHaveCount(2)
                ->first()
                ->name
                ->toBe('Joe Doe');

            return true;
        });
});
