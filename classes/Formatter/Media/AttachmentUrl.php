<?php

declare(strict_types=1);

namespace AC\Formatter\Media;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

class AttachmentUrl implements Formatter
{

    public function format(Value $value): Value
    {
        if ( ! $value->get_id()) {
            return $value;
        }

        $url = wp_get_attachment_url($value->get_id());

        if ( ! $url) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value($url);
    }

}