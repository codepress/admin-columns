<?php

declare(strict_types=1);

namespace AC\Formatter\Media;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

class FileSize implements Formatter
{

    private ?string $format;

    public function __construct(string $format = null)
    {
        $this->format = $format;
    }

    public function format(Value $value): Value
    {
        $file = get_attached_file($value->get_id());

        if ( ! file_exists($file)) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $file_size = filesize($file);

        if ($file_size <= 0) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        switch ($this->format) {
            case 'kb' :
                $file_size = $file_size / 1024;
                $file_size = sprintf('%s KB', number_format($file_size, 2));
                break;
            case 'mb' :
                $file_size = $file_size / (1024 * 1024);
                $file_size = sprintf('%s MB', number_format($file_size, 2));
                break;
            case 'gb' :
                $file_size = $file_size / (1024 * 1024 * 1024);
                $file_size = sprintf('%s GB', number_format($file_size, 2));
                break;
        }

        return $value->with_value(
            $file_size
        );
    }

}