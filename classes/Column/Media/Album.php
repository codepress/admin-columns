<?php

namespace AC\Column\Media;

use AC\Column;

class Album extends Column
{

    public function __construct()
    {
        $this->set_type('column-meta_album')
             ->set_group('media-audio')
             ->set_label(__('Album', 'codepress-admin-columns'));
    }

    public function get_value($id)
    {
        $meta = get_post_meta($id, '_wp_attachment_metadata', true);

        $value = $meta['album'] ?? null;

        return $value ?: $this->get_empty_char();
    }

}