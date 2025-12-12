<?php

namespace AC\Value\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;

class ImageUrl implements Formatter
{

    public function format(Value $value)
    {
        $data = $value->get_value();

        if ( ! $data) {
            throw new ValueNotFoundException('No image data found.');
        }

        $url = $this->convert_to_url($data);

        if ( ! $url) {
            throw new ValueNotFoundException('No image URL found.');
        }

        return $value->with_value($url);
    }

    private function convert_to_url($id_or_url): ?string
    {
        if (is_numeric($id_or_url)) {
            return wp_get_attachment_url($id_or_url) ?: null;
        }

        if (is_string($id_or_url) && ac_helper()->string->is_image($id_or_url)) {
            return $id_or_url;
        }

        return null;
    }

}