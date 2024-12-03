<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Post;

use AC\Setting\Formatter;
use AC\Type\Value;

class PingStatus implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(
            'open' === get_post_field('ping_status', $value->get_id(), 'raw')
        );
    }

}