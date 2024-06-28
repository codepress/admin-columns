<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Post;

use AC\Setting\Formatter;
use AC\Type\Value;

class GmtDate implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(
            get_post_field('post_date_gmt', $value->get_value())
        );
    }

}