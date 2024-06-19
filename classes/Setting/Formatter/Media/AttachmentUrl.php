<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Media;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class AttachmentUrl implements Formatter
{

    public function format(Value $value): Value
    {
        if ( ! $value->get_id()) {
            return $value;
        }

        return $value->with_value(wp_get_attachment_url($value->get_id()));
    }

}