<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Media;

use AC\Setting\Formatter;
use AC\Type\Value;

class FileName implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(
            ac_helper()->html->link(
                wp_get_attachment_url($value->get_id()),
                ac_helper()->image->get_file_name($value->get_id())
            )
        );
    }

}