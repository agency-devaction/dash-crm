<?php

namespace App\Livewire\Admin\Users;

use App\Enum\Can;
use Livewire\Attributes\On;
use Livewire\Component;
use RuntimeException;

class Impersonate extends Component
{
    public function render(): string
    {
        return <<<BLADE
            <div><x-button
                icon="o-eye"
                class="btn-circle btn-ghost btn-xs"
                wire:click="impersonate"
            /></div>
        BLADE;
    }

    #[On('impersonate')]
    public function impersonate(int $id): void
    {
        $this->authorize(Can::BE_AN_ADMIN->value);

        if (auth()->id() === $id) {
            throw new RuntimeException('You cannot impersonate yourself.');
        }

        session()->put('impersonator', auth()->id());
        session()->put('impersonate', $id);

        $this->redirect(route('welcome'));
    }
}
