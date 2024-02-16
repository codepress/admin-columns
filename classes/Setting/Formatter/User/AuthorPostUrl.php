<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\User;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class AuthorPostUrl implements Formatter
{

    public function format(Value $value): Value
    {
        $url = get_author_posts_url($value->get_id());

        return $url
            ? $value->with_value(sprintf('<a href="%s">%s</a>', $url, $value->get_value()))
            : $value;
    }

}