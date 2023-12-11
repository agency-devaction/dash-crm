<?php

namespace App\Listeners\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Random\RandomException;

class CreateValidationCode
{
    /**
     * @throws RandomException
     */
    public function handle(Registered $event): void
    {
        /** @var User $user */
        $user = $event->user;

        $user->email_verification_code = random_int(100000, 999999);
        $user->save();
    }
}
