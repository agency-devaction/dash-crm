<?php

use App\Livewire\Admin\Users\{Impersonate, StopImpersonate};
use App\Models\User;

use function Pest\Laravel\{actingAs, get};
use function PHPUnit\Framework\{assertSame, assertTrue};

it('should add a key impersonate to the session with the given user', function () {
    $admin = User::factory()->admin()->create();
    $user  = User::factory()->create();

    actingAs($admin);

    Livewire::test(Impersonate::class)
        ->call('impersonate', $user->id);

    assertTrue(session()->has('impersonate'));
    assertTrue(session()->has('impersonator'));

    assertSame(session()->get('impersonate'), $user->id);
    assertSame(session()->get('impersonator'), $admin->id);
});

it('should make sure that we are logged with impersonate user', function () {
    $admin = User::factory()->admin()->create();
    $user  = User::factory()->create();

    actingAs($admin);

    expect(auth()->user()?->id)->toBe($admin->id);

    Livewire::test(Impersonate::class)
        ->call('impersonate', $user->id)
        ->assertRedirect(route('welcome'));

    get(route('welcome'))
        ->assertSee(__("You're impersonating :name, click here", ['name' => $user->name]))
        ->assertSee(__("to stop impersonating."));

    expect(auth()->id())->toBe($user->id);
});

it('should be able to stop impersonate', function () {
    $admin = User::factory()->admin()->create();
    $user  = User::factory()->create();

    actingAs($admin);

    expect(auth()->user()?->id)->toBe($admin->id);

    Livewire::test(Impersonate::class)
        ->call('impersonate', $user->id)
        ->assertRedirect(route('welcome'));

    Livewire::test(StopImpersonate::class)
        ->call('stopImpersonate', $user->id)
        ->assertRedirect(route('admin.users'));

    expect(session('impersonate'))->toBeNull();

    get(route('admin.dashboard'))
        ->assertDontSeeLivewire(__("You're impersonating :name, click here to stop the impersonate.", ['name' => $user->name]));

    expect(auth()->id())->toBe($admin->id);
});
