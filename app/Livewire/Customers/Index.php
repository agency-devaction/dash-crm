<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\{Attributes\Computed, Component, WithPagination};

/**
 * @property-read LengthAwarePaginator|Customer[] $customers
 * @property-read array $headers
 */
class Index extends Component
{
    use WithPagination;

    public ?string $search = null;

    public array $sortBy = ['column' => 'id', 'direction' => 'desc'];

    public int $perPage = 15;
    public function render(): View
    {
        return view('livewire.customers.index');
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
        ];
    }

    #[Computed]
    public function customers(): LengthAwarePaginator
    {
        ds($this->sortBy);

        return Customer::query()
            ->when(
                $this->search,
                fn ($query, $search) => $query->whereRaw(
                    'LOWER(name) LIKE ?',
                    ["%$search%"]
                )
                    ->orWhereRaw('LOWER(email) LIKE ?', ["%$search%"])
            )
            ->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage);
    }
}
