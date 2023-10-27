<?php

namespace App\Models;

use App\Enum\Can;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

trait HasPermissions
{
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function givePermissionTo(Can|string $key): void
    {
        $permissionKey = $key instanceof Can ? $key->value : $key;

        $this->permissions()->firstOrCreate(['key' => $permissionKey]);

        Cache::forget($this->getCacheKey());

        Cache::rememberForever($this->getCacheKey(), fn () => $this->permissions);
    }

    public function hasPermissionTo(Can|string $key): bool
    {
        $permissionKey = $key instanceof Can ? $key->value : $key;

        /** @var Collection $permissions */
        $permissions = Cache::get($this->getCacheKey(), $this->permissions);

        return $permissions
            ->where('key', '=', $permissionKey)
            ->isNotEmpty();
    }

    private function getCacheKey(): string
    {
        return "user::{$this->id}::permissions";
    }
}
