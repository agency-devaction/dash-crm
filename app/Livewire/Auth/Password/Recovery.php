<?php

namespace App\Livewire\Auth\Password;

use App\Models\User;
use App\Notifications\Auth\PasswordRecoverNotification;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

class Recovery extends Component
{
    public ?string $message = null;

    #[Rule(['required', 'email'])]
    public string $email = '';

    #[Layout('components.layouts.guest')]
    public function render(): View
    {
        return view('livewire.auth.password.recovery');
    }

    public function startPasswordRecovery(): void
    {
        $this->validate();

        $user = User::whereEmail($this->email)->first();

        $user?->notify(new PasswordRecoverNotification());

        $this->message = "You will receive an email with a link to reset your password.";
    }
}
