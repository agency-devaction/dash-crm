<x-card
    class="mx-auto w-[450px]"
    title="Register"
    subtitle="Register a new account"
    shadow separator
>
    <x-form wire:submit="submit">
        <x-input label="Name" wire:model="name"/>
        <x-input label="Email" wire:model="email"/>
        <x-input label="Email Confirmation" wire:model="email_confirmation"/>
        <x-input label="Password" wire:model="password" type="password"/>

        <x-slot:actions>
            <div class="w-full flex items-center justify-between">
                <a wire:navigate href="{{ route('login') }}"
                   class="link link-primary">{{ __('I already have an account') }}</a>

                <div>
                    <x-button label="{{ __('Cancel') }}" type="reset"/>
                    <x-button label="{{ __('Register') }}" class="btn-primary" type="submit" spinner="submit"/>
                </div>
            </div>
        </x-slot:actions>
    </x-form>
</x-card>
