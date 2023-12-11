<?php

use App\Listeners\Auth\{CreateValidationCode};
use App\Models\User;
use App\Notifications\Auth\ValidationCodeNotification;
use Illuminate\Auth\Events\Registered;

use function PHPUnit\Framework\assertTrue;

beforeEach(function () {
    Notification::fake();
});

it('should create a new validate and save in the users table', function () {
    $user = User::factory()->create([
        'email_verification_code' => null,
        'email_verified_at'       => null,
    ]);
    $event = new Registered($user);

    $lister = new CreateValidationCode();

    try {
        $lister->handle($event);
    } catch (Random\RandomException $e) {
        Log::error($e->getMessage());
    }

    $user->refresh();
    expect($user->email_verification_code)->not->toBeNull()
        ->and($user->email_verification_code)->toBeNumeric();

    assertTrue(Str::length((string)$user->email_verification_code) === 6);

});

it('should send that new code to the user via email', function () {
    $user = User::factory()->create([
        'email_verification_code' => null,
        'email_verified_at'       => null,
    ]);
    $event = new Registered($user);

    $lister = new CreateValidationCode();

    try {
        $lister->handle($event);
    } catch (Random\RandomException $e) {
        Log::error($e->getMessage());
    }

    Notification::assertSentTo(
        $user,
        ValidationCodeNotification::class,
    );
});

it('making sure that the listener to send the code is linked to the Registered event', function () {
    Event::fake();

    Event::assertListening(
        Registered::class,
        CreateValidationCode::class,
    );

});
