<?php

namespace App\Livewire\Auth\Password;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\{DB, Hash};
use Illuminate\Support\Str;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Password;
use stdClass;

class Reset extends Component
{
    public ?string $token = null;

    #[Rule(['required', 'email', 'confirmed'])]
    public ?string $email = null;

    public ?string $email_confirmation = null;

    #[Rule(['required', 'confirmed'])]
    public ?string $password = null;

    public ?string $password_confirmation = null;

    public function mount(string $token = null, string $email = null): void
    {
        $this->token = $token;
        $this->email = $email;

        if ($this->tokenNotValid()) {
            session()->flash('status', 'Invalid token');
            $this->redirectRoute('login');
        }
    }
    public function render(): View
    {
        return view('livewire.auth.password.reset');
    }

    public function updatePassword(): void
    {
        $this->validate();

        Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, $password) {
                $user->password       = $password;
                $user->remember_token = Str::random(60);
                $user->save();

                event(new PasswordReset($user));
            }
        );

        session()->flash('status', __('Your password has been reset.'));

        $this->redirect(route('dashboard'), navigate: true);
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
