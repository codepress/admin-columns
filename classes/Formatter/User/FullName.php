<?php

declare(strict_types=1);

namespace AC\Formatter\User;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

class FullName implements Formatter
{

    public function format(Value $value): Value
    {
        $user = get_userdata($value->get_id());

        if ( ! $user) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $full_name = trim($user->first_name . ' ' . $user->last_name);

        if ( ! $full_name) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value(
            $full_name
        );
    }

}