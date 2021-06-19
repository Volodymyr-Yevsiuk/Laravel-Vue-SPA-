<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'image',
        'description',
        'address',
        'employees',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'name' => 'string',
        'image' => 'string',
        'description' => 'string',
        'employees' => 'integer',
        'address' => 'string'
    ];

    // Generate uuid
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) \Illuminate\Support\Str::uuid();
        });
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }

    //Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function products()
    {
        return $this->hasMany(Company::class);
    }
}
