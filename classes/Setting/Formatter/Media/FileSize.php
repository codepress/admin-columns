<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Media;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class FileSize implements Formatter
{

    public function format(Value $value): Value
    {
        $file = get_attached_file($value->get_id());

        if ( ! file_exists($file)) {
            return new Value(null);
        }

        return $value->with_value(
            ac_helper()->file->get_readable_filesize(filesize($file))
        );
    }

}