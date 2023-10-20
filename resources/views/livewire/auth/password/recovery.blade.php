<x-card
    class="mx-auto w-[450px]"
    title="Login"
    subtitle=""
    shadow
    separator
>
    <div>
        @if($message)
            <x-alert t icon="o-exclamation-triangle" class="alert-success mb-4">
                <span> You will receive an email with a link to reset your password.</span>
            </x-alert>
        @endif
    </div>
    <x-form wire:submit="startPasswordRecovery">
        <x-input label="{{ __('Email') }}" wire:model="email"/>
        <x-slot:actions>
            <div class="w-full flex items-center justify-between">
                <a wire:navigate href="{{ route('login') }}"
                   class="link link-primary">{{ __('Never mind, get to be email') }}</a>

                <div>
                    <x-button label="{{ __('Submit') }}" class="btn-primary" type="submit" spinner="submit"/>
                </div>
            </div>
        </x-slot:actions>
    </x-form>
</x-card>

