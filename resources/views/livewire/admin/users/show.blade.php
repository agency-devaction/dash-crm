<div>
    <x-drawer wire:model="modal" class="w-1/3 p-5" right>
        {{ $user?->name }}

        <hr class="my-5"/>
        @if($user)
            <div class="space-y-2">
                <x-input readonly label="{{ __('Name') }}" :value="$user->name"/>
                <x-input readonly label="{{ __('Email') }}" :value="$user->email"/>
                <x-input readonly label="{{ __('Created At') }}" :value="$user->created_at->format('d/m/Y H:i')"/>
                <x-input readonly label="{{ __('Updated At') }}" :value="$user->updated_at?->format('d/m/Y H:i')"/>
                <x-input readonly label="{{ __('Deleted At') }}" :value="$user->deleted_at?->format('d/m/Y H:i')"/>
                <x-input readonly label="{{ __('Deleted By') }}" :value="$user->deletedBy?->name"/>
            </div>
        @endif

        <hr class="my-5"/>
        {{-- Livewire: Server side  --}}
        <x-button label="{{ __('Close') }}" @click="$wire.modal = false" class="btn-primary"/>
    </x-drawer>
</div>
