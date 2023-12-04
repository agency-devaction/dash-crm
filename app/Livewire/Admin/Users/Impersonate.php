<?php

namespace App\Livewire\Admin\Users;

use Livewire\Attributes\On;
use Livewire\Component;

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
        session()->put('impersonate', $id);

        $this->redirect(route('welcome'));
    }
}
