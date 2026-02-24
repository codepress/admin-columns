<?php

namespace AC\Formatter\Media;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Helper\Date;
use AC\Type\Value;
use DateTimeZone;

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
                $timestamp = (int)$exif_value;

                if ( ! $timestamp) {
                    throw ValueNotFoundException::from_id($value->get_id());
                }

                return $value->with_value(
                    wp_date(
                        Date::create()->get_date_time_format(),
                        $timestamp,
                        new DateTimeZone('UTC')
                    )
                );
            case 'keywords' :
                if ( ! is_array($exif_value)) {
                    throw ValueNotFoundException::from_id($value->get_id());
                }

                return (new Formatter\ImplodeRecursive())->format($value->with_value($exif_value));

            default:
                return $value->with_value($exif_value);
        }
    }

}