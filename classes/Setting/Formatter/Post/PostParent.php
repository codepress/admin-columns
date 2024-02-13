<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Post;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class PostParent implements Formatter
{

    public function format(Value $value): Value
    {
        $parent = ac_helper()->post->get_raw_field('post_parent', $value->get_id());

        if ($parent == 0) {
            return new Value(false);
        }

        return new Value($parent);
    }

}