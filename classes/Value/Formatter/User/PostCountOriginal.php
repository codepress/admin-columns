<?php

declare(strict_types=1);

namespace AC\Value\Formatter\User;

use AC\Setting\Formatter;
use AC\Type\Value;

class PostCountOriginal implements Formatter
{

    public function format(Value $value)
    {
        return $value->with_value(
            count_user_posts($value->get_id())
        );
    }

}