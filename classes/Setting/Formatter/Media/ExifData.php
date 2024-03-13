<?php

namespace AC\Setting\Formatter\Media;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class ExifData implements Formatter
{

    private $exif_data;

    public function __construct(string $exif_data)
    {
        $this->exif_data = $exif_data;
    }

    // TODO test
    public function format(Value $value): Value
    {
        $exif_value = ((array)$value->get_value())[$this->exif_data] ?? '';

        if (false !== $exif_value) {
            switch ($this->exif_data) {
                case 'created_timestamp' :
                    $exif_value = $value->with_value(
                        ac_format_date(
                            get_option('date_format') . '' . get_option('time_format'),
                            $exif_value
                        )
                    );
                    break;

                case 'keywords' :
                    $exif_value = $value->with_value(ac_helper()->array->implode_recursive(', ', $exif_value));
                    break;
            }
        }

        return $value->with_value($exif_value);
    }

}