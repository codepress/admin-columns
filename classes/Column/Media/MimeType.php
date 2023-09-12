<?php

namespace AC\Column\Media;

use AC\Column;

/**
 * @since 2.0
 */
class MimeType extends Column
{

    public function __construct()
    {
        $this->set_type('column-mime_type')
             ->set_label(__('Mime Type', 'codepress-admin-columns'));
    }

    public function get_value($id)
    {
        $value = $this->get_raw_value($id);

        return $value ?: $this->get_empty_char();
    }

    public function get_raw_value($id)
    {
        return get_post_field('post_mime_type', $id);
    }

}