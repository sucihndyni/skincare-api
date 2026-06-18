<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'token',
        'token_created_at',
        'refresh_token',
        'refresh_token_created_at'
    ];

    protected $hidden = [
        'password',
        'token',
        'refresh_token'
    ];

    protected $casts = [
        'token_created_at' => 'datetime',
        'refresh_token_created_at' => 'datetime',
    ];
}