<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Livewire\Component;

class Register extends Component
{
    public ?string $name;

    public ?string $email;

    public ?string $email_confirmation;

    public ?string $password;

    public function submit(): void
    {
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
