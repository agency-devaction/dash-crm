<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\Models\HasPermissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class User.
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property ?string $email_verified_at
 * @property ?string $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $restored_at
 * @property int|null $restored_by
 * @property int|null $deleted_by
 * @property int|null $email_verification_code
 */
class User extends Authenticatable implements Auditable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasPermissions;
    use SoftDeletes;
    use AuditableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'restored_at',
        'restored_by',
        'deleted_by',
        'email_verification_code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function restoredBy(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'restored_by');
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'deleted_by');
    }
}
