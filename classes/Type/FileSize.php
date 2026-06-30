<?php

declare(strict_types=1);

namespace AC\Type;

class FileSize
{
    private int $bytes;

    public function __construct(int $bytes)
    {
        $this->bytes = $bytes;
    }

    public function get_bytes(): int
    {
        return $this->bytes;
    }

    public function format(int $decimals = 2, string $fallback = ''): string
    {
        if ($this->bytes <= 0) {
            return '';
        }

        $filesize_units = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        $i = (int)floor(log($this->bytes, 1024));

        $filesize = round($this->bytes / pow(1024, $i), $decimals);

        $unit = $filesize_units[$i] ?? null;

        if (! $filesize || ! $unit) {
            return $fallback;
        }

        return sprintf('%s %s', $filesize, $unit);
    }

    public function __toString(): string
    {
        return $this->format();
    }

}
