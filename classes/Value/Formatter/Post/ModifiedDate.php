<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Post;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;

class ModifiedDate implements Formatter
{

    public function format(Value $value): Value
    {
        $date = get_post($value->get_id())->post_modified ?? null;

        if ( ! $date) {
            throw new ValueNotFoundException();
        }

        return $value->with_value(
            strtotime($date)
        );
    }

}