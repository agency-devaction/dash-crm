<?php

namespace App\Audit;

use OwenIt\Auditing\Contracts\{Auditable, Resolver};

class ImpersonatorIdResolver implements Resolver
{
    public static function resolve(Auditable $auditable): ?int
    {
        $impersonateId = session()->get('impersonator');

        return is_int($impersonateId) ? $impersonateId : null;
    }
}
