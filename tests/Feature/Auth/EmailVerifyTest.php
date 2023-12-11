<?php

use App\Listeners\Auth\CreateValidationCode;
use App\Models\User;
use Illuminate\Auth\Events\Registered;

use function PHPUnit\Framework\assertTrue;

it('should create a new validate and save in the users table', function () {
    $user = User::factory()->create([
        'email_verification_code' => null,
        'email_verified_at'       => null,
    ]);
    $event = new Registered($user);

    $lister = new CreateValidationCode();
    $lister->handle($event);

    $user->refresh();
    expect($user->email_verification_code)->not->toBeNull()
        ->and($user->email_verification_code)->toBeNumeric();

    assertTrue(Str::length($user->email_verification_code) === 6);

});

it('should send that new code to the user via email', function () {

})->todo();

it('making sure that the listener to send the code is linked to the Registered event', function () {

})->todo();
