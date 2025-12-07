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
        $parent_id = get_post($value->get_id())->post_parent ?? null;

        if ( ! $parent_id) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return new Value($parent_id);
    }

}