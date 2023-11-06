<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use App\Notifications\AccountRestored;
use Exception;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Mary\Traits\Toast;
use RuntimeException;

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

    /**
     * @throws Exception
     */
    public function restore(): void
    {
        $this->validate();

        $user = auth()->user();

        if (!$user) {
            throw new RuntimeException('User not found');
        }

        if ($this->user->is(auth()->user())) {
            $this->addError('confirmation', 'You cannot Restore yourself.');

            return;
        }

        $this->user->restore();

        $this->user->restored_at = now();
        $this->user->restored_by = $user->id;
        $this->user->save();

        $this->user->notify(new AccountRestored());
        $this->dispatch('user::restored');

        $this->success('User restored successfully.');
    }
}
