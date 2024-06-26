<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Post;

use AC\Setting\Formatter;
use AC\Type\Value;

class PostFormatIcon implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->get_value()
            ? $value->with_value(
                ac_helper()->html->tooltip(
                    '<span class="ac-post-state-format post-state-format post-format-icon post-format-' . esc_attr(
                        $value->get_value()
                    ) . '"></span>',
                    get_post_format_string($value->get_value())
                )
            )
            : $value->with_value(false);
    }

}