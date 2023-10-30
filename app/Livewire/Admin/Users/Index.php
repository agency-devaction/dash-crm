<?php

namespace App\Livewire\Admin\Users;

use App\Enum\Can;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * @property-read LengthAwarePaginator $users
 * @property-read array $headers
 */
class Index extends Component
{
    public ?string $search = null;

    public function mount(): void
    {
        $this->authorize(Can::BE_AN_ADMIN->value);
    }
    public function render(): View
    {
        return view('livewire.admin.users.index');
    }

    #[Computed]
    public function users(): LengthAwarePaginator
    {
        return User::query()
            ->when(
                $this->search,
                fn (Builder $query): Builder => $query->whereRaw(
                    'lower(name) LIKE ?',
                    ["%{$this->search}%"]
                )
            )
            ->orWhere(
                fn (Builder $query): Builder => $query->whereRaw(
                    'lower(email) LIKE ?',
                    ["%{$this->search}%"]
                )
            )
            ->orderBy('id', 'desc')
            ->paginate();
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'permission', 'label' => 'permission'],
        ];
    }
}
