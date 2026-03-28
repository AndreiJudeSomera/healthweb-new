<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PasswordOtp extends Model
{
    use HasFactory;

    // Table name will default to "password_otps" which matches our migration.
    protected $fillable = [
        'email',
        'otp',
        'expires_at',
        'verified',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified'   => 'boolean',
    ];

    /** Scopes */
    public function scopeForEmail($query, string $email)
    {
        return $query->where('email', $email);
    }

    public function scopeNotExpired($query)
    {
        return $query->where('expires_at', '>', now());
    }

    /** Helpers */
    public function isExpired(): bool
    {
        return $this->expires_at instanceof Carbon
            ? $this->expires_at->isPast()
            : now()->greaterThan($this->expires_at);
    }
}
