<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

class FileSizeReadable implements Formatter
{

    public function format(Value $value): Value
    {
        $bytes = $value->get_value() ?? 0;

        if ( ! is_numeric($bytes) || $bytes <= 0) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value(
            ac_helper()->file->get_readable_filesize((int)$bytes)
        );
    }

}