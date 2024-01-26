<?php

namespace AC\Column\Media;

use AC\Column;

class Artist extends Column
{

    public function __construct()
    {
        $this->set_type('column-meta_artist')
             ->set_group('media-audio')
             ->set_label(__('Artist', 'codepress-admin-columns'));
    }

    public function get_value($id)
    {
        $meta = get_post_meta($id, '_wp_attachment_metadata', true);

        $value = $meta['artist'] ?? null;

        return $value ?: $this->get_empty_char();
    }

}