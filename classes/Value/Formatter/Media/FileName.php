<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Media;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;

class FileName implements Formatter
{

    public function format(Value $value): Value
    {
        $filename = ac_helper()->image->get_file_name($value->get_id());

        if ( ! $filename) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value($filename);
    }

}