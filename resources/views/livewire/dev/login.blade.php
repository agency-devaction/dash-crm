<div class="flex items-center space-x-2 bg-yellow-300 justify-end">
    <x-select icon="o-user"
              :options="$this->users"
              wire:model="selectedUser"
              class="flex-1"
              placeholder="Select an user"
              placeholder-value="0"
              class="select-sm"
    />

    <x-button
        icon="o-power"
        class="btn-circle btn-success btn-sm"
        wire:click="login"
    />
</div>

