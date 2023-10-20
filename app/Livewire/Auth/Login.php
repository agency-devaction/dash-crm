<?php

namespace App\Livewire\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\{Auth, RateLimiter};
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Request;

class Login extends Component
{
    public ?string $email = '';

    public ?string $password = '';

    #[Layout('components.layouts.guest')]
    public function render(): View
    {
        return view('livewire.auth.login');
    }

    public function tryToLogin(): void
    {
        if(RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            $this->addError('rateLimitExceeded', trans('auth.throttle', [
                'seconds' => RateLimiter::availableIn($this->throttleKey()),
            ]));

            return;
        }

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {

            RateLimiter::hit($this->throttleKey());

            $this->addError('invalidCredentials', trans('auth.failed'));

            return;
        }
        $this->redirect(route('dashboard'));
    }

    /**
     * @return string
     */
    private function throttleKey(): string
    {
        return Str::transliterate(Str::lower((string)$this->email) . '|' . Request::ip());
    }
}
