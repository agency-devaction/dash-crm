<div>
    <x-header title="Users" separator progress-indicator=""/>

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
        <x-checkbox
            label="{{ __('Show Delete users') }}"
            wire:model.live="search_trashed"
            class="checkbox-primary"
            right
            tight
        />
    </div>
    <x-table :headers="$this->headers" :rows="$this->users">
        @scope('header_name', $header)
        {{ $header['label'] }} ⬆️
        @endscope

        @scope('cell_permission', $user)
        @foreach($user->permissions as $permission)
            <x-badge :value="$permission->key" class="badge-primary"/>
        @endforeach
        @endscope

        @scope('actions', $user)
        @unless($user->trashed())
            <x-button icon="o-trash" wire:click="delete{{ $user->id }}" class="btn-error btn-outline" spinner/>
        @else
            <x-button icon="o-arrow-path" wire:click="restore{{ $user->id }}" class="btn-success btn-ghost"/>
        @endunless

        @endscope
    </x-table>
</div>
