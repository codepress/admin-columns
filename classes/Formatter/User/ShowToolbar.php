<?php

declare(strict_types=1);

namespace AC\Formatter\User;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;
use WP_User;

class ShowToolbar implements Formatter
{

    public function format(Value $value): Value
    {
        $user = get_userdata($value->get_id());

        if ( ! $user instanceof WP_User) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return new Value('true' === ($user->show_admin_bar_front ?? null));
    }

}