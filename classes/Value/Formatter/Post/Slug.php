<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Post;

use AC\Setting\Formatter;
use AC\Type\Value;

class Slug implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(urldecode(get_post_field('post_name', $value->get_id(), 'raw')));
    }

}