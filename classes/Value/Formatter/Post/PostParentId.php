<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Post;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;

class PostParentId implements Formatter
{

    public function format(Value $value): Value
    {
        $parent = (int)ac_helper()->post->get_raw_field('post_parent', (int)$value->get_id());

        if ( ! $parent) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return new Value($parent);
    }

}