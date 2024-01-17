<?php

namespace AC\Column\Comment;

use AC;
use AC\Column;

/**
 * @since 2.0
 */
class Excerpt extends Column
{

    public function __construct()
    {
        $this->set_type('column-excerpt')
             ->set_label(__('Content', 'codepress-admin-columns'));
    }

    public function get_raw_value($id)
    {
        $comment = get_comment($id);

        return $comment->comment_content;
    }

    public function register_settings()
    {
        $this->add_setting(new AC\Settings\Column\WordLimit(15));
    }

}