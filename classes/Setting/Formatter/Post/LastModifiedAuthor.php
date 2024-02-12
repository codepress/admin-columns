<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Post;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class LastModifiedAuthor implements Formatter
{

    public function format(Value $value): Value
    {
        // TODO Test if null ID is possible
        return new Value(get_post_meta($value->get_id(), '_edit_last', true));
    }

}