<?php

namespace App\Livewire\Auth\Password;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Recovery extends Component
{
    public ?string $message = null;

    public function render(): View
    {
        return view('livewire.auth.password.recovery');
    }

    public function startPasswordRecovery(): void
    {
        $this->message = "You will receive an email with a link to reset your password.";
    }
}
