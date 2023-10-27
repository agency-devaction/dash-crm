<?php

namespace App\Providers;

use App\Enum\Can;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        foreach (Can::cases() as $can) {
            Gate::define(
                Str::of($can->value)->snake('-')->toString(),
                fn ($user) => $user->hasPermissionTo($can)
            );
        }
    }
}
