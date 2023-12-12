<?php

use App\Listeners\Auth\{CreateValidationCode};
use App\Livewire\Auth\{EmailValidation, Register};
use App\Models\User;
use App\Notifications\Auth\ValidationCodeNotification;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;

use function Pest\Laravel\actingAs;
use function PHPUnit\Framework\assertTrue;

beforeEach(function () {
    Notification::fake();
});

describe('after registration', function () {
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
});

describe('validate page', function () {
    it('should redirect to validate page after registration', function () {
        Livewire::test(Register::class)
            ->set('name', 'John Doe')
            ->set('email', 'jhondoe@gmail.com')
            ->set('email_confirmation', 'jhondoe@gmail.com')
            ->set('password', 'password')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertRedirect(route('auth.email-verification'));
    });

    it('should check if the code is valid', function () {
        $user = User::factory()->withValidationCode()->create();

        actingAs($user);

        Livewire::test(EmailValidation::class)
            ->set('code', 123456)
            ->call('handle')
            ->assertHasErrors(['code']);
    });

    it('should be able to send a new code to the user', function () {
        $user    = User::factory()->withValidationCode()->create();
        $oldCode = $user->email_verification_code;

        actingAs($user);

        Livewire::test(EmailValidation::class)
            ->call('sendNewCode');

        $user->refresh();

        expect($user->email_verification_code)->not->toBe($oldCode);

        Notification::assertSentTo($user, ValidationCodeNotification::class);
    });

    it('should update email_verification_at and clean code is the code validating', function () {
        $user = User::factory()->withValidationCode()->create();

        actingAs($user);

        Livewire::test(EmailValidation::class)
            ->set('code', $user->email_verification_code)
            ->call('handle')
            ->assertHasNoErrors()
            ->assertRedirect(RouteServiceProvider::HOME);

        expect($user->refresh()->email_verified_at)->not->toBeNull()
            ->and($user->email_verification_code)->toBeNull();
    });

});
