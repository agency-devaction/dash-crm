<?php

namespace App\Traits\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

trait AuthenticatedUser
{
    public function getAuthenticatedUser(): User
    {
        $user = Auth::user();

        if ($user === null) {
            throw new RuntimeException('User is not authenticated');
        }

        return $user;
    }
}
