<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Post;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class PostParent implements Formatter
{

    public function format(Value $value): Value
    {
        $parent = (int)ac_helper()->post->get_raw_field('post_parent', (int)$value->get_id());

        return new Value($parent ?: null);
    }

}