<x-card
    class="mx-auto w-[450px]"
    title="Login"
    subtitle=""
    shadow
    separator
>
    @if($errors->hasAny('invalidCredentials', 'rateLimitExceeded'))
        <x-alert t icon="o-exclamation-triangle" class="alert-warning mb-4">
            @error('invalidCredentials')
            {{ $message }}
            @enderror

            @error('rateLimitExceeded')
            {{ $message }}
            @enderror
        </x-alert>
    @endif
    <div>

    </div>
    <x-form wire:submit="tryToLogin">
        <x-input label="{{ __('Email') }}" wire:model="email"/>
        <x-input label="{{ __('Password') }}" wire:model="password" type="password"/>

        <x-slot:actions>
            <div class="w-full flex items-center justify-between">
                <a wire:navigate href="{{ route('auth.register') }}"
                   class="link link-primary">{{ __('I want to create an account') }}</a>

                <div>
                    <x-button label="{{ __('login') }}" class="btn-primary" type="submit" spinner="submit"/>
                </div>
            </div>
        </x-slot:actions>
    </x-form>
</x-card>
