<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    public ?User $user;

    public bool $modal = false;
    public function render(): View
    {
        return view('livewire.admin.users.show');
    }

    #[On('user::show')]
    public function loadUser(int $userId): void
    {
        $this->user  = User::withTrashed()->find($userId);
        $this->modal = true;
    }
}
