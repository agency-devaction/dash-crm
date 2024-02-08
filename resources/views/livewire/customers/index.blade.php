<div>
    <div>
        <x-header title="Customer" separator progress-indicator/>
        <div class="flex gap-3 mb-4">
            <div class="w-1/3">
                <x-input icon="o-magnifying-glass-circle"
                         label="{{ __('Search') }}"
                         placeholder="{{ __('Search') }}"
                         class="input-md"
                         wire:model.live="search"
                />
            </div>
            <x-select
                wire:model.live="perPage"
                label="{{ __('Per page') }}"
                :options="[['id' => 5,'name' => 5],['id' => 15,'name' => 15],['id' => 25,'name' => 25],['id' => 50,'name' => 50]]"
            />

            <x-checkbox
                label="{{ __('Show Delete users') }}"
                wire:model.live="search_trashed"
                class="checkbox-primary"
                right
                tight
            />
        </div>
        <x-table :headers="$this->headers" :rows="$this->customers">
            
            @scope('actions', $customer)
            <div class="flex items-center space-x-2">

                <x-button id="show-{{$customer->id}}"
                          wire:key="show-{{$customer->id}}-{{ mt_rand() }}"
                          icon="o-eye"
                          class="btn-sm btn-ghost"
                          wire:click="showUser({{$customer->id}})"
                          spinner

                />
            </div>
            @endscope
        </x-table>
        <div class="mt-4">
            {{ $this->customers->links(data: ['scrollTo' => false]) }}
        </div>
    </div>
    @foreach($this->customers as $customer)
        {{ $customer->name }}
    @endforeach
</div>
