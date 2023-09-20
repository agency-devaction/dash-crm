<?php

use App\Livewire\Auth\Register;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Livewire\Livewire;

use function Pest\Laravel\{assertDatabaseCount, assertDatabaseHas};

it('should render the component', function () {
    Livewire::test(Register::class)
        ->assertOk();
});

it('should be able to register a new user in the system', function () {
    Livewire::test(Register::class)
        ->set('name', 'John Doe')
        ->set('email', 'jhondoe@gmail.com')
        ->set('email_confirmation', 'jhondoe@gmail.com')
        ->set('password', 'password')
        ->call('submit')
        ->assertHasNoErrors()
    ->assertRedirect(RouteServiceProvider::HOME);

    assertDatabaseHas('users', [
        'name'  => 'John Doe',
        'email' => 'jhondoe@gmail.com']);

    assertDatabaseCount('users', 1);

    expect(auth()->check())
        ->and(auth()->user())
        ->id
        ->toBe(User::first()->id);
});

test(
    'required fields',
    function ($f) {

        Livewire::test(Register::class)
            ->set($f->field, $f->value)
            ->call('submit')
            ->assertHasErrors([$f->field => $f->rule]);
    }
)
    ->with([
        'name::required'     => (object)['field' => 'name', 'value' => '', 'rule' => 'required'],
        'name::max:255'      => (object)['field' => 'name', 'value' => str_repeat('*', 256), 'rule' => 'max'],
        'email::email'       => (object)['field' => 'email', 'value' => 'not-an-email', 'rule' => 'email'],
        'email::max'         => (object)['field' => 'email', 'value' => str_repeat('*' . '@doe.com', 256), 'rule' => 'max'],
        'email::confirmed'   => (object)['field' => 'email', 'value' => 'joe@gmail.com', 'rule' => 'confirmed'],
        'password::required' => (object)['field' => 'password', 'value' => '', 'rule' => 'required'],
    ]);
