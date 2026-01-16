<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

final class AvatarUrl implements Formatter
{

    public function format(Value $value): Value
    {
        $url = get_avatar_url(
            $value->get_value()
        );

        if ( ! $url) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value($url);
    }

}