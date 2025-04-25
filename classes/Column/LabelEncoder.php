<?php

declare(strict_types=1);

namespace AC\Column;

class LabelEncoder
{

    public function encode(string $url): string
    {
        return $this->convert($url);
    }

    public function decode(string $url): string
    {
        return $this->convert($url, 'decode');
    }

    public function convert(string $url, string $action = 'encode'): string
    {
        $input = [site_url(), '[cpac_site_url]'];

        if ('decode' === $action) {
            $input = array_reverse($input);
        }

        return stripslashes(str_replace($input[0], $input[1], trim($url)));
    }

}