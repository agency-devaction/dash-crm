<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use App\Notifications\AccountDeleted;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Mary\Traits\Toast;

class Delete extends Component
{
    use Toast;

    public User $user;

    public bool $modal = false;

    #[Rule(['required', 'confirmed'])]
    public string $confirmation = 'OK I AM SURE';

    public ?string $confirmation_confirmation = null;
    public function render(): View
    {
        return view('livewire.admin.users.delete');
    }

    public function destroy(): void
    {
        $this->validate();

        if ($this->user->is(auth()->user())) {
            $this->addError('confirmation', 'You cannot delete yourself.');

            return;
        }

        $this->user->delete();
        $this->user->notify(new AccountDeleted());
        $this->dispatch('user::deleted');

        $this->success('User deleted successfully.');
    }
}
