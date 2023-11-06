<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use App\Notifications\AccountRestored;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Mary\Traits\Toast;

class Restore extends Component
{
    use Toast;

    public User $user;

    public bool $modalRestore = false;

    #[Rule(['required', 'confirmed'])]
    public string $confirmation = 'OK I AM SURE';

    public ?string $confirmation_confirmation = null;
    public function render(): View
    {
        return view('livewire.admin.users.restore');
    }

    public function restore(): void
    {
        $this->validate();

        if ($this->user->is(auth()->user())) {
            $this->addError('confirmation', 'You cannot Restore yourself.');

            return;
        }

        $this->user->restore();
        $this->user->notify(new AccountRestored());
        $this->dispatch('user::restored');

        $this->success('User restored successfully.');
    }
}
