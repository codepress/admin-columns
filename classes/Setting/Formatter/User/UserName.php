<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\User;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class UserName implements Formatter
{

    public function format(Value $value): Value
    {
        $user = get_userdata($value->get_id());

        $display = $user->user_login;

        if (current_user_can('edit_user', $user->ID)) {
            $display = sprintf('<a href="%s">%s</a>', get_edit_user_link($user->ID), $display);
        }

        if (in_array($user->user_login, get_super_admins(), true)) {
            $display .= ' &mdash; ' . __('Super Admin');
        }

        return $value->with_value($display);
    }

}