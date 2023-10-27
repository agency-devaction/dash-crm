<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

trait HasPermissions
{
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function givePermissionTo(string $key): void
    {
        $this->permissions()->firstOrCreate(['key' => $key]);

        Cache::forget($this->getCacheKey());

        Cache::rememberForever($this->getCacheKey(), fn () => $this->permissions);
    }

    public function hasPermissionTo(string $key): bool
    {
        /** @var Collection $permissions */
        $permissions = Cache::get($this->getCacheKey(), $this->permissions);

        return $permissions
            ->where('key', '=', $key)
            ->isNotEmpty();
    }

    private function getCacheKey(): string
    {
        return "user::{$this->id}::permissions";
    }
}
