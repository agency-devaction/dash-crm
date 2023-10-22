<?php

namespace App\Livewire\Auth\Password;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\{DB, Hash};
use Livewire\Component;
use stdClass;

class Reset extends Component
{
    public ?string $token = null;

    public function mount(string $token): void
    {
        $this->token = $token;

        if ($this->tokenNotValid()) {
            session()->flash('status', 'Invalid token');
            $this->redirectRoute('login');
        }
    }
    public function render(): View
    {
        return view('livewire.auth.password.reset');
    }

    private function tokenNotValid(): bool
    {
        $tokens = DB::table('password_reset_tokens')->get(['token']);

        /** @var stdClass $t */
        foreach ($tokens as $t) {
            if (Hash::check((string) $this->token, $t->token)) {
                return false;
            }
        }

        return true;
    }
}
