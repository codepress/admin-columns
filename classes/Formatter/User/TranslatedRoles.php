<?php

declare(strict_types=1);

namespace AC\Formatter\User;

use AC;
use AC\Exception\ValueNotFoundException;
use AC\Type\Value;

class TranslatedRoles implements AC\Formatter
{

    public function format(Value $value)
    {
        $user = get_userdata($value->get_id());

        if ( ! $user) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $roles = [];

        foreach ((new AC\Helper\UserRoles())->find_all() as $role) {
            if ( ! in_array($role->get_name(), $user->roles, true)) {
                continue;
            }

            $roles[] = ac_helper()->html->tooltip($role->get_translate_label(), $role->get_name());
        }

        natcasesort($roles);

        if (empty($roles)) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value(
            implode(', ', $roles)
        );
    }

}