<div>
    <x-button icon="o-arrow-path" class="btn-success btn-ghost" spinner @click="$wire.modalRestore = true"/>

    <x-modal wire:model="modalRestore" title="{{ __('Confirm Restoring') }}"
             subtitle=" You are restoring the user {{ $user->name }}"
    >

        @error('confirmation')
        <x-alert icon="o-exclamation-triangle" class="alert-error mb-4">
            {{ $message }}
        </x-alert>
        @enderror

        <x-input label="Write OK I AM SURE to confirm restore user"
                 wire:model="confirmation_confirmation"/>

        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.modalRestore = false"/>
            <x-button label="Confirm" class="btn-primary" wire:click="restore"/>
        </x-slot:actions>
    </x-modal>
</div>
