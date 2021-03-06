<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'name' => 'string'
    ];

    public const IS_USER = 1;
    public const IS_ADMIN = 2;

    //Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
