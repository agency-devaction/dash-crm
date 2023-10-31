<?php

namespace App\Livewire\Admin\Users;

use App\Enum\Can;
use App\Models\{Permission, User};
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\{Builder, Collection};
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\{Component, WithPagination};

/**
 * @property-read LengthAwarePaginator $users
 * @property-read array $headers
 */
class Index extends Component
{
    use WithPagination;

    public ?string $search = null;

    public array $search_permission = [];

    public Collection $permissionsToSearch;

    public bool $search_trashed = false;

    public string $sortDirection = 'asc';

    public string $sortByColumn = 'id';

    public int $perPage = 15;

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
                    ->orWhere('email', 'LIKE', "%{$this->search}%")
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
            ->orderBy($this->sortByColumn, $this->sortDirection)
            ->paginate($this->perPage);
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'sortByColumn' => $this->sortByColumn, 'sortDirection' => $this->sortDirection],
            ['key' => 'name', 'label' => 'Name', 'sortByColumn' => $this->sortByColumn, 'sortDirection' => $this->sortDirection],
            ['key' => 'email', 'label' => 'Email', 'sortByColumn' => $this->sortByColumn, 'sortDirection' => $this->sortDirection],
            ['key' => 'permission', 'label' => 'permission', 'sortByColumn' => $this->sortByColumn, 'sortDirection' => $this->sortDirection],
        ];
    }

    public function filterPermissions(?string $value = null): void
    {
        $this->permissionsToSearch = Permission::query()
            ->where('key', 'LIKE', "%{$value}%")
            ->orderBy('key')
            ->get();
    }

    public function sortBy(string $column, string $direction): void
    {
        $this->sortByColumn  = $column;
        $this->sortDirection = $direction;
    }
}
