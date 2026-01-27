<?php

namespace AC\Helper;

class File
{

    public function get_readable_filesize(int $bytes, int $decimals = 2, string $fallback = ''): string
    {
        if ($bytes <= 0) {
            return '';
        }

        $filesize_units = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        $i = (int)floor(log($bytes, 1024));

        $filesize = round($bytes / pow(1024, $i), $decimals);

        $unit = $filesize_units[$i] ?? null;

        if ( ! $filesize || ! $unit) {
            return $fallback;
        }

        return sprintf('%s %s', $filesize, $unit);
    }

}