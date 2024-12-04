<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Media;

use AC\Setting\Formatter;
use AC\Type\Value;

class FileSize implements Formatter
{

    public function format(Value $value): Value
    {
        $file = get_attached_file($value->get_id());

        if ( ! file_exists($file)) {
            return new Value(null);
        }

        $file_size = filesize($file);

        if ($file_size <= 0) {
            return new Value(null);
        }

        return $value->with_value(
            $file_size
        );
    }

}