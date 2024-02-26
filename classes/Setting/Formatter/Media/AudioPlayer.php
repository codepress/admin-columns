<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Media;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

// TODO Stefan
class AudioPlayer implements Formatter
{

    public function format(Value $value): Value
    {
        $src = $this->is_valid_mime_type($value->get_id())
            ? wp_get_attachment_url($value->get_id())
            : null;

        if ( ! $src) {
            return $value->with_value(false);
        }

        return $value->with_value(
            sprintf(
                '<audio controls preload="none" src="%s">%s</audio>',
                esc_url($src),
                __('No support for audio player', 'codepress-admin-columns')
            )
        );
    }

    private function get_valid_mime_types()
    {
        return (array)apply_filters(
            'ac/column/audio_player/valid_mime_types',
            ['audio/mpeg', 'audio/flac', 'audio/wav'],
            $this
        );
    }

    private function is_valid_mime_type($id)
    {
        return in_array($this->get_mime_type($id), $this->get_valid_mime_types());
    }

    private function get_mime_type($id)
    {
        return (string)get_post_field('post_mime_type', $id);
    }

    public function get_raw_value($id)
    {
        return $this->is_valid_mime_type($id)
            ? wp_get_attachment_url($id)
            : false;
    }

}