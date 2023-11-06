@props(['header', 'name'])

<div wire:click="sortBy('{{$name}}', '{{ $header['sortDirection'] === 'asc' ? 'desc': 'asc'}}')"
     class="cursor-pointer">
    {{ $header['label'] }} @if($header['sortByColumn'] === $name)
        <x-icon :name="$header['sortDirection'] === 'asc' ? 'o-arrow-up' : 'o-arrow-down'"/>
    @endif
</div>
