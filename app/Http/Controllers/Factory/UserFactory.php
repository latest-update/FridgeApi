<?php


namespace App\Http\Controllers\Factory;


use App\Http\Controllers\Factory\Relations\OneToMany;
use App\Models\Role;
use App\Models\User;

class UserFactory
{
    public static function changeRole(User $user, Role $role): OneToMany
    {
        return new OneToMany($user, $role, 'role');
    }

    public static function createAdmin(User $user)
    {
        $admin = Role::find(2);
        return new OneToMany($user, $admin, 'role');
    }

    public static function createUser(User $user)
    {
        return new OneToMany($user, Role::first(), 'role');
    }
}
