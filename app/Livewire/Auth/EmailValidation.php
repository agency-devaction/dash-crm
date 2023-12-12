<?php

namespace App\Livewire\Auth;

use App\Events\User\SendNewCode;
use App\Notifications\Register\WelcomeNotification;
use App\Providers\RouteServiceProvider;
use App\Traits\User\AuthenticatedUser;
use Closure;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use RuntimeException;

class EmailValidation extends Component
{
    use AuthenticatedUser;

    public string $code = '';
    public function render(): View
    {
        return view('livewire.auth.email-validation');
    }

    public function handle(): void
    {
        $this->validate([
            'code' => function (string $attribute, $value, Closure $fail) {
                $user = auth()->user();

                if ($user === null) {
                    $fail('User is not authenticated');

                    return;
                }

                if (!is_numeric($value)) {
                    $fail('Invalid code format');

                    return;
                }

                if ($user->email_verification_code !== (int)$value) {
                    $fail('Invalid code');
                }
            },
        ]);

        $user = $this->getAuthenticatedUser();

        $user->email_verified_at       = now();
        $user->email_verification_code = null;
        $user->save();

        $user->notify(new WelcomeNotification());

        $this->redirect(RouteServiceProvider::HOME);
    }

    public function sendNewCode(): void
    {
        $user = auth()->user();

        if ($user === null) {
            throw new RuntimeException('User is not authenticated');
        }
        SendNewCode::dispatch($user);
    }
}
