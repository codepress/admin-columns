<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Post;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class LastModifiedAuthor implements Formatter
{

    public function format(Value $value): Value
    {
        $user_id = get_post_meta($value->get_id(), '_edit_last', true);

        if ( ! get_userdata($user_id)) {
            return new Value(null);
        }

        return new Value($user_id);
    }

}