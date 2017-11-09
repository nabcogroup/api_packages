<?php
/**
 * Created by PhpStorm.
 * User: arnold.mercado
 * Date: 11/7/2017
 * Time: 4:33 PM
 */

namespace KielPack\LaraLibs\Users;


use KielPack\LaraLibs\Base\BaseModel;

class Role extends BaseModel
{
    protected $table = "roles";

    public function users() {

        return $this->belongsToMany(User::class,'user_roles','role_id','user_id');

    }
}