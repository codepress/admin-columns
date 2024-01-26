<?php

namespace AC\Column\Comment;

use AC\Column;
use AC\Settings\Column\WordLimit;

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
        $this->add_setting(new WordLimit(15));
    }

}