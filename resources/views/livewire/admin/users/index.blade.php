<div>
    <x-header title="Users" separator progress-indicator=""/>

    <div class="flex justify-between mb-4">
        <div class="w-1/3">
            <x-input icon="o-magnifying-glass-circle"
                     label="{{ __('Search') }}"
                     placeholder="{{ __('Search') }}"
                     class="input-md"
                     wire:model="search"
            />
        </div>
    </div>
    <x-table :headers="$this->headers" :rows="$this->users">
        @scope('cell_permission', $user)
        @foreach($user->permissions as $permission)
            <x-badge :value="$permission->key" class="badge-primary"/>
        @endforeach
        @endscope

        @scope('actions', $user)
        <x-button icon="o-trash" wire:click="delete{{ $user->id }}"/>
        @endscope
    </x-table>
</div>