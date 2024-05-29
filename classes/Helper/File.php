<?php

namespace AC\Helper;

class File
{

    public function get_readable_filesize(int $bytes, int $decimals = 2, string $fallback = ''): string
    {
        $filesize = $this->get_readable_filesize_as_array($bytes, $decimals);

        if ( ! $filesize) {
            return $fallback;
        }

        return implode(' ', $filesize);
    }

    public function get_readable_filesize_as_array(int $bytes, int $decimals = 2): array
    {
        if ( ! $bytes) {
            return [];
        }

        $filesize_units = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        $i = (int)floor(log($bytes, 1024));

        return [
            round($bytes / pow(1024, $i), $decimals),
            $filesize_units[$i],
        ];
    }

}