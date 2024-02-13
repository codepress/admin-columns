<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Post;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class ModifiedDate implements Formatter
{

    public function format(Value $value): Value
    {
        return new Value(get_post_field('post_modified', $value->get_value()));
    }

}