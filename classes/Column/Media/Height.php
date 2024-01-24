<?php

namespace AC\Column\Media;

use AC\Column;

class Height extends Column
{

    public function __construct()
    {
        $this->set_type('column-height')
             ->set_group('media-image')
             ->set_label(__('Height', 'codepress-admin-columns'));
    }

    public function get_value($id): string
    {
        $meta = get_post_meta($id, '_wp_attachment_metadata', true);

        $value = $meta['height'] ?? null;

        return $value
            ? $value . 'px'
            : $this->get_empty_char();
    }

}