<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Post;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class PingStatus implements Formatter
{

    public function format(Value $value): Value
    {
        $ping_status = get_post_field('ping_status', $value->get_id(), 'raw');

        return $value->with_value(ac_helper()->icon->yes_or_no('open' === $ping_status, $ping_status));
    }

}