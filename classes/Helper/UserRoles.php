<?php

declare(strict_types=1);

namespace AC\Helper;

use AC\Type;

final class UserRoles
{

    public function find_all(bool $allow_non_editable_roles = false): Type\UserRoles
    {
        return $allow_non_editable_roles
            ? $this->find_all_roles()
            : $this->find_all_editable_roles();
    }

    public function find_all_roles(): Type\UserRoles
    {
        return function_exists('wp_roles')
            ? $this->create_roles(wp_roles()->roles)
            : new Type\UserRoles();
    }

    public function find_all_editable_roles(): Type\UserRoles
    {
        return function_exists('get_editable_roles')
            ? $this->create_roles(get_editable_roles())
            : new Type\UserRoles();
    }

    private function create_roles(array $roles_data): Type\UserRoles
    {
        $roles = new Type\UserRoles();

        foreach ($roles_data as $role_name => $role) {
            $roles->add(
                new Type\UserRole($role_name, $role['name'])
            );
        }

        return $roles;
    }

}