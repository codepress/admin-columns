<?php

namespace AC\Column\Media;

use AC\Column;

class AlternateText extends Column
{

    public function __construct()
    {
        $this->set_type('column-alternate_text')
             ->set_group('media-image')
             ->set_label(__('Alternative Text', 'codepress-admin-columns'));
    }

    public function get_value($id)
    {
        return get_post_meta($id, '_wp_attachment_image_alt', true) ?: $this->get_empty_char();
    }

}