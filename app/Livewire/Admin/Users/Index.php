<?php

namespace App\Livewire\Admin\Users;

use App\Enum\Can;
use App\Models\{Permission, User};
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\{Builder, Collection};
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

    public array $search_permission = [];

    public Collection $permissionsToSearch;

    public bool $search_trashed = false;

    public string $sortField = 'asc';

    public string $sortByColumn = 'id';

    public function mount(): void
    {
        $this->authorize(Can::BE_AN_ADMIN->value);
        $this->filterPermissions();
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
            ->when(
                $this->search_permission,
                fn (Builder $query): Builder => $query->whereHas(
                    'permissions',
                    fn (Builder $query): Builder => $query->whereIn(
                        'id',
                        $this->search_permission
                    )
                )
            )
            ->when(
                $this->search_trashed,
                fn (Builder $query): Builder => $query->onlyTrashed()
            )
            ->orderBy($this->sortByColumn, $this->sortField)
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

    public function filterPermissions(?string $value = null): void
    {
        $this->permissionsToSearch = Permission::query()
            ->where('key', 'LIKE', "%{$value}%")
            ->orderBy('key')
            ->get();
    }
}
