<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Support\Facades\Event;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

class Register extends Component
{
    #[Rule(['required', 'max:255'])]
    public ?string $name = null;

    #[Rule(['required', 'email', 'max:255', 'confirmed', 'unique:users,email'])]
    public ?string $email = null;

    public ?string $email_confirmation = null;

    #[Rule(['required'])]
    public ?string $password = null;

    public function submit(): void
    {
        $this->validate();
        $user = User::query()->create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => $this->password,
        ]);

        auth()->login($user);

        Event::dispatch(new Registered($user));

        $this->redirect(route('auth.email-verification'));
    }

    #[Layout('components.layouts.guest')]
    public function render(): View|Factory|Application
    {
        return view('livewire.auth.register');
    }
}
