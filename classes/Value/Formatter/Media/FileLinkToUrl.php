<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Media;

use AC\Setting\Formatter;
use AC\Type\Value;

class FileLinkToUrl implements Formatter
{

    public function format(Value $value): Value
    {
        $url = wp_get_attachment_url($value->get_id());

        if ( ! $url) {
            return $value;
        }

        return $value->with_value(
            ac_helper()->html->link(
                $url,
                (string)$value
            )
        );
    }

}