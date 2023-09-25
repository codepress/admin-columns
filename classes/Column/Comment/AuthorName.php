<?php

namespace AC\Column\Comment;

use AC\Column;

class AuthorName extends Column
{

    public function __construct()
    {
        $this->set_type('column-author_name');
        $this->set_label(__('Author Name', 'codepress-admin-columns'));
    }

    public function get_value($id)
    {
        return $this->get_raw_value($id);
    }

    public function get_raw_value($id)
    {
        return get_comment($id)->comment_author;
    }

}