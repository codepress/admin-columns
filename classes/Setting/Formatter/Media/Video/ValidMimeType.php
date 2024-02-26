<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Media\Video;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class ValidMimeType implements Formatter
{

    public function format(Value $value): Value
    {
        if ( ! $this->is_valid_mime_type($value->get_id())) {
            return new Value(null);
        }

        return $value;
    }

    private function is_valid_mime_type($id)
    {
        return in_array(
            $this->get_mime_type($id),
            $this->get_valid_mime_types(),
            true
        );
    }

    private function get_valid_mime_types()
    {
        return (array)apply_filters(
            'ac/column/audio_player/valid_mime_types',
            [
                'video/mp4',
                'video/webm',
                'video/quicktime',
            ]
        );
    }

    private function get_mime_type($id)
    {
        return (string)get_post_field('post_mime_type', $id);
    }

}