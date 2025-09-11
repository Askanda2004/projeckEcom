<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    // use HasFactory, Notifiable;
    use Notifiable;

    // 👇 บอก Eloquent ว่า primary key ชื่อ user_id
    protected $primaryKey = 'user_id';
    public $incrementing = true;   // เป็น auto increment
    protected $keyType = 'int';    // ชนิดเป็น int

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ช่วยเช็ค role แบบสั้น ๆ
    public function isRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function products()
    {
        return $this->hasMany(\App\Models\Product::class, 'seller_id', 'user_id');
    }
}
