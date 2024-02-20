<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Media;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class FileSize implements Formatter
{

    public function format(Value $value): Value
    {
        $abs = get_attached_file($value->get_id());

        if ( ! file_exists($abs)) {
            return $value->with_value(false);
        }

        return $value->with_value(ac_helper()->file->get_readable_filesize(filesize($abs)));
    }

}