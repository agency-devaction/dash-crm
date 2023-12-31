<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class Logout extends Component
{
    public function render(): string
    {
        return <<<BLADE
            <div><x-button
                icon="o-power"
                class="btn-circle btn-ghost btn-xs"
                wire:click="logout"
            /></div>
        BLADE;
    }

    public function logout(): void
    {
        auth()->logout();

        session()->regenerateToken();
        session()->flush();

        $this->redirect(route('login'));
    }
}
