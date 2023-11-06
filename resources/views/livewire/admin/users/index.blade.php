@php use App\Enum\Can; @endphp
<div>
    <x-header title="Users" separator progress-indicator/>
    <div class="flex gap-3 mb-4">
        <div class="w-1/3">
            <x-input icon="o-magnifying-glass-circle"
                     label="{{ __('Search') }}"
                     placeholder="{{ __('Search') }}"
                     class="input-md"
                     wire:model.live="search"
            />
        </div>
        <x-choices
            label="{{ __('Select by permissions') }}"
            placeholder="{{ __('Select by permission') }}"
            wire:model.live="search_permission"
            :options="$permissionsToSearch"
            search-function="filterPermissions"
            searchable="true"
            option-label="key"
            no-result-text="No results found"
        />

        <x-select
            wire:model.live="perPage"
            label="{{ __('Per page') }}"
            :options="[['id' => 5,'name' => 5],['id' => 15,'name' => 15],['id' => 25,'name' => 25],['id' => 50,'name' => 50]]"
        />

        <x-checkbox
            label="{{ __('Show Delete users') }}"
            wire:model.live="search_trashed"
            class="checkbox-primary"
            right
            tight
        />
    </div>
    <x-table :headers="$this->headers" :rows="$this->users">
        @scope('header_id', $header)
        <x-table.th :$header name="id"/>
        @endscope

        @scope('header_name', $header)
        <x-table.th :$header name="name"/>
        @endscope

        @scope('header_email', $header)
        <x-table.th :$header name="email"/>
        @endscope

        @scope('cell_permission', $user)
        @foreach($user->permissions as $permission)
            <x-badge :value="$permission->key" class="badge-primary"/>
        @endforeach
        @endscope

        @scope('actions', $user)
        @can(Can::BE_AN_ADMIN->value)

            @unless($user->trashed())
                @unless($user->is(auth()->user()))
                    <div>
                        <livewire:admin.users.delete :$user wire:key="delete-{{$user->id}}-{{ mt_rand() }}"/>
                    </div>
                @endif
            @else
                <x-button icon="o-arrow-path" wire:click="restore{{ $user->id }}" class="btn-success btn-ghost"/>
            @endunless
        @endcan
        @endscope
    </x-table>
    <div class="mt-4">
        {{ $this->users->links(data: ['scrollTo' => false]) }}
    </div>
</div>
