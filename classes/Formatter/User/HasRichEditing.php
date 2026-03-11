<?php

declare(strict_types=1);

namespace AC\Formatter\User;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

class HasRichEditing implements Formatter
{

    public function format(Value $value): Value
    {
        $user = get_userdata($value->get_id());

        if ( ! $user) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return new Value('true' === ($user->rich_editing ?? null));
    }

}