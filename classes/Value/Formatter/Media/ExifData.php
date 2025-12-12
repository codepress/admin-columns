<?php

namespace AC\Value\Formatter\Media;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;

class ExifData implements Formatter
{

    private string $exif_key;

    public function __construct(string $exif_key)
    {
        $this->exif_key = $exif_key;
    }

    public function format(Value $value): Value
    {
        $exif_value = $value->get_value()[$this->exif_key] ?? null;

        if (null === $exif_value) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        switch ($this->exif_key) {
            case 'created_timestamp' :
                return $value->with_value(
                    ac_format_date(
                        get_option('date_format') . '' . get_option('time_format'),
                        $exif_value
                    )
                );
            case 'keywords' :
                if (is_array($exif_value)) {
                    return $value->with_value(
                        ac_helper()->array->implode_recursive(', ', $exif_value)
                    );
                }
            default:
                return $value->with_value($exif_value);
        }
    }

}