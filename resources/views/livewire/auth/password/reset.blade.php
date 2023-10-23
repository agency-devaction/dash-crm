<x-card
    class="mx-auto w-[450px]"
    title="Login"
    subtitle=""
    shadow
    separator
>
    @if( $message = session()->get('status') )
        <x-alert t icon="o-exclamation-triangle" class="alert-error mb-4">
            <span>{{ $message }}</span>
        </x-alert>
    @endif
    <x-form wire:submit="updatePassword">
        <x-input label="{{ __('Email') }}" value="{{ $this->obfuscateEmail }}" readonly/>
        <x-input label="{{ __('Email') }}" wire:model="email_confirmation"/>
        <x-input label="{{ __('Password') }}" wire:model="password" type="password"/>
        <x-input label="{{ __('Password Confirmation') }}" wire:model="password_confirmation" type="password"/>
        <x-slot:actions>
            <div class="w-full flex items-center justify-between">
                <a wire:navigate href="{{ route('login') }}"
                   class="link link-primary">{{ __('Never mind, get to be email') }}</a>

                <div>
                    <x-button label="{{ __('Reset') }}" class="btn-primary" type="submit" spinner="submit"/>
                </div>
            </div>
        </x-slot:actions>
    </x-form>
</x-card>

