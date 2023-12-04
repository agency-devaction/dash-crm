<div class="bg-yellow-300 px-4 text-sm font-bold text-yellow-900">
    {{ __("You're impersonating :name, click here", ['name' => auth()->user()->name]) }}
    <x-button
        icon="o-power"
        class="btn-circle btn-error btn-sm"
        wire:click="stopImpersonate"

    />
    {{ __("to stop impersonating.")}}
</div>
