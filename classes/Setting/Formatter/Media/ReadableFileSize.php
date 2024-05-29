<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Media;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class ReadableFileSize implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(
            $value->with_value(ac_helper()->file->get_readable_filesize((int)$value->get_value()))
        );
    }

}