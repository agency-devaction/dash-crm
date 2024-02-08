@php use App\Enum\Can; @endphp
<div>
    <div>
        <x-header title="Users" separator progress-indicator/>
        <div class="flex gap-3 mb-4">
            <div class="w-1/3">
                <x-input icon="o-magnifying-glass-circle"
                         label="{{ __('Search') }}"
                         placeholder="{{ __('Search') }}"
                         class="input-md"
                         wire:model.live="search"
                />
            </div>
            <x-choices
                label="{{ __('Select by permissions') }}"
                placeholder="{{ __('Select by permission') }}"
                wire:model.live="search_permission"
                :options="$permissionsToSearch"
                search-function="filterPermissions"
                searchable="true"
                option-label="key"
                no-result-text="No results found"
            />

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
        <x-table :headers="$this->headers" :rows="$this->users">
            @scope('header_id', $header)
            <x-table.th :$header name="id"/>
            @endscope

            @scope('header_name', $header)
            <x-table.th :$header name="name"/>
            @endscope

            @scope('header_email', $header)
            <x-table.th :$header name="email"/>
            @endscope

            @scope('cell_permission', $user)
            @foreach($user->permissions as $permission)
                <x-badge :value="$permission->key" class="badge-primary"/>
            @endforeach
            @endscope

            @scope('actions', $user)
            <div class="flex items-center space-x-2">

                <x-button id="show-{{$user->id}}"
                          wire:key="show-{{$user->id}}-{{ mt_rand() }}"
                          icon="o-eye"
                          class="btn-sm btn-ghost"
                          wire:click="showUser({{$user->id}})"
                          spinner

                />

                @can(Can::BE_AN_ADMIN->value)

                    @unless($user->trashed())
                        @unless($user->is(auth()->user()))
                            <div>
                                <x-button id="impersonate-{{$user->id}}"
                                          wire:key="impersonate-{{$user->id}}-{{ mt_rand() }}"
                                          icon="o-user"
                                          class="btn-sm btn-ghost"
                                          wire:click="impersonate({{$user->id}})"
                                          spinner
                                />
                            </div>
                            <div>
                                <livewire:admin.users.delete :$user wire:key="delete-{{$user->id}}-{{ mt_rand() }}"/>
                            </div>
                        @endif
                    @else
                        <div>
                            <livewire:admin.users.restore :$user wire:key="restore-{{$user->id}}-{{ mt_rand() }}"/>
                        </div>
                    @endunless
                @endcan
            </div>
            @endscope
        </x-table>
        <div class="mt-4">
            {{ $this->users->links(data: ['scrollTo' => false]) }}
        </div>
        <livewire:admin.users.show/>
        <livewire:admin.users.impersonate/>
    </div>
</div>
