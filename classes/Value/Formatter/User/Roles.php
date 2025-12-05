<?php

declare(strict_types=1);

namespace AC\Value\Formatter\User;

use AC;
use AC\Exception\ValueNotFoundException;
use AC\Helper\UserRoles;
use AC\Type\Value;

class Roles implements AC\Setting\Formatter
{

    private $allow_non_editable_roles;

    public function __construct(bool $allow_non_editable_roles)
    {
        $this->allow_non_editable_roles = $allow_non_editable_roles;
    }

    public function format(Value $value)
    {
        $user = get_userdata($value->get_id());

        if ( ! $user) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $allowed_roles = $this->get_allowed_roles();

        $labels = [];

        foreach ($user->roles as $role_name) {
            $role_object = $this->get_allowed_role($role_name);

            if ( ! $role_object) {
                continue;
            }

            $labels[] = $role_object->get_translate_label();
        }

        return $value->with_value(
            implode(', ', $labels)
        );
    }

    private function get_allowed_roles(): AC\Type\UserRoles
    {
        static $editable_roles;

        if (null === $editable_roles) {
            $editable_roles = (new UserRoles())->find_all($this->allow_non_editable_roles);
        }

        return $editable_roles;
    }

    private function get_allowed_role(string $role): ?AC\Type\UserRole
    {
        foreach ($this->get_allowed_roles() as $editable_role) {
            if ($role === $editable_role->get_name()) {
                return $editable_role;
            }
        }

        return null;
    }

}