<?php

declare(strict_types=1);

namespace AC\Formatter\Media;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

class FileSize implements Formatter
{

    private ?string $format;

    private ?int $decimals;

    public function __construct(string $format = null, ?int $decimals = 2)
    {
        $this->format = $format ?? 'bytes';
        $this->decimals = $decimals;
    }

    public function format(Value $value): Value
    {
        $file = get_attached_file($value->get_id());

        if ( ! file_exists($file)) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $bytes = filesize($file);

        if ($bytes === false || $bytes <= 0) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        switch ($this->format) {
            case 'kb' :
                $size = $bytes / 1024;
                break;
            case 'mb' :
                $size = $bytes / (1024 * 1024);
                break;
            case 'gb' :
                $size = $bytes / (1024 * 1024 * 1024);
                break;
            case 'tb' :
                $size = $bytes / (1024 * 1024 * 1024 * 1024);
                break;
            default :
                $size = $bytes;
        }

        if (null !== $this->decimals) {
            $size = round($size, $this->decimals);
        }

        return $value->with_value(
            (float)$size
        );
    }

}