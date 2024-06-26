<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Post;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;

class LastModifiedAuthor implements Formatter
{

    public function format(Value $value): Value
    {
        $user_id = get_post_meta($value->get_id(), '_edit_last', true);

        if ( ! get_userdata($user_id)) {
            throw new ValueNotFoundException();
        }

        return new Value($user_id);
    }

}