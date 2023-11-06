<div>
    <x-button icon="o-trash" class="btn-error btn-outline" spinner @click="$wire.modal = true"/>

    <x-modal wire:model="modal" title="{{ __('Confirm Deletion') }}"
             subtitle=" You are deleting the user {{ $user->name }}"
    >

        @error('confirmation')
        <x-alert icon="o-exclamation-triangle" class="alert-error mb-4">
            {{ $message }}
        </x-alert>
        @enderror

        <x-input label="Write OK I AM SURE to confirm deletion user"
                 wire:model="confirmation_confirmation"/>

        <x-slot:actions>
            {{-- Note `onclick` is HTML --}}
            <x-button label="Cancel" @click="$wire.modal = false"/>
            <x-button label="Confirm" class="btn-primary" wire:click="destroy"/>
        </x-slot:actions>
    </x-modal>
</div>
