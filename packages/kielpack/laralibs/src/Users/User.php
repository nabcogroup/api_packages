<?php

namespace KielPack\LaraLibs\Users;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use KielPack\LaraLibs\Traits\UserRoleTrait;


class User extends Authenticatable
{
    use UserRoleTrait,
        Notifiable;


    protected $fillable = [
        'username', 'full_name', 'email', 'password',
    ];

    protected $hidden = ['password', 'remember_token',];

    protected $table = "users";


    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public function isPasswordMatch($attempt)
    {
        if (Hash::check($attempt, $this->getAuthPassword())) {
            return true;
        }

        return false;
    }
}