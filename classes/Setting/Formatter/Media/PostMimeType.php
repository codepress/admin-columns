<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Media;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class PostMimeType implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(
            get_post_field('post_mime_type', $value->get_id())
        );
    }

}