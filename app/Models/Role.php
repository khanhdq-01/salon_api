<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    public const ID_ADMIN = 1;
    public const ID_OWNER = 2;
    public const ID_STAFF = 3;
    public const ID_CUSTOMER = 4;

    public const CUSTOMER = 'customer';
    public const OWNER = 'owner';
    public const ADMIN = 'admin';
    public const STAFF = 'staff';

    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function scopeNamed($query, string $name)
    {
        return $query->where('name', $name);
    }
}
