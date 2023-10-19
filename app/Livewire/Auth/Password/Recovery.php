<?php

namespace App\Livewire\Auth\Password;

use App\Models\User;
use App\Notifications\Auth\PasswordRecoverNotification;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Recovery extends Component
{
    public ?string $message = null;

    public string $email = '';

    public function render(): View
    {
        return view('livewire.auth.password.recovery');
    }

    public function startPasswordRecovery(): void
    {
        $user = User::whereEmail($this->email)->first();

        $user?->notify(new PasswordRecoverNotification());

        $this->message = "You will receive an email with a link to reset your password.";
    }
}
