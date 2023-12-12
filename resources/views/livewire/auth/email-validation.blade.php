<x-card
    class="mx-auto w-[450px]"
    title="Email validation"
    subtitle=""
    shadow
    separator
>
    @if($sendNewCodeMessage)
        <x-alert t icon="o-envelope" class="alert-warning mb-4">
            <span>{{ $sendNewCodeMessage }}</span>
        </x-alert>
    @endif

    <x-form wire:submit="handle">
        <x-input label="{{ __('Code') }}" wire:model="code"/>

        <x-slot:actions>
            <div class="w-full flex items-center justify-between">
                <a wire:click="sendNewCode"
                   class="link link-primary">{{ __('Send a new code') }}</a>
                <div>
                    <x-button label="{{ __('Validate') }}" class="btn-primary" type="submit" spinner="submit"/>
                </div>
            </div>
        </x-slot:actions>
    </x-form>
</x-card>
