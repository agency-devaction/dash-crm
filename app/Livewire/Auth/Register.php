<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Livewire\Attributes\Rule;
use Livewire\Component;

class Register extends Component
{
    #[Rule(['required'])]
    public ?string $name = null;

    #[Rule(['required'])]
    public ?string $email = null;

    public ?string $email_confirmation = null;

    #[Rule(['required'])]
    public ?string $password = null;

    public function submit(): void
    {
        $this->validate();
        User::query()->create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => $this->password,
        ]);
    }
    public function render(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('livewire.auth.register');
    }
}
