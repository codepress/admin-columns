<?php

declare(strict_types=1);

namespace AC\Formatter\User;

use AC\Formatter;
use AC\Setting\Type\Value;

final class Gravatar implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(get_avatar($value->get_id()));
    }

}