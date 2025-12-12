<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;

class StripTags implements Formatter
{

    public function format(Value $value): Value
    {
        if ('' === (string)$value) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value(
            wp_strip_all_tags((string)$value)
        );
    }

}