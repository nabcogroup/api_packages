<?php

namespace KielPack\LaraLibs\Traits;


trait UserRoleTrait
{
    protected $fieldName;

    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        }
        else {
            return $this->hasRole($role);
        }

        return false;
    }

    public function hasRole($role)
    {
        $fieldName = $this->getRoleFieldName();
        if ($this->roles()->where($fieldName, $role)->first()) {
            return true;
        }

        return false;
    }

    public function isAdmin()
    {
        $fieldName = $this->getRoleFieldName();
        if ($this->roles()->where($fieldName, 'admin')->first()) {
            return true;
        }

        return false;
    }

    public function isPasswordMatch($attempt)
    {
        if (Hash::check($attempt, $this->getAuthPassword())) {
            return true;
        }
        return false;
    }

    public function getRoleToString()
    {
        $roles = $this->roles()->get();
        $strRoleName = "";

        foreach ($roles as $role) {
            $strRoleName = $role->name . ",";
        }

        return substr($strRoleName, 0, strlen($strRoleName)-1);
    }
}