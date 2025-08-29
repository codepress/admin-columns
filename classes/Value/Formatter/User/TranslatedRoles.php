<?php

declare(strict_types=1);

namespace AC\Value\Formatter\User;

use AC;
use AC\Type\Value;
use WP_User;

class TranslatedRoles implements AC\Setting\Formatter
{

    public function format(Value $value)
    {
        $user = new WP_User($value->get_id());

        $translated_roles = ac_helper()->user->translate_roles($user->roles);

        natcasesort($translated_roles);

        $roles = [];

        foreach ($translated_roles as $role => $label) {
            $roles[] = ac_helper()->html->tooltip($label, $role);
        }

        if (empty($roles)) {
            throw AC\Exception\ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value(
            implode(', ', $roles)
        );
    }

}