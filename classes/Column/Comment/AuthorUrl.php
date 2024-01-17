<?php

namespace AC\Column\Comment;

use AC\Column;

class AuthorUrl extends Column
{

    public function __construct()
    {
        $this->set_type('column-author_url');
        $this->set_label(__('Author URL', 'codepress-admin-columns'));
    }

    public function get_value($id)
    {
        $raw_value = $this->get_raw_value($id);

        return $raw_value
            ? ac_helper()->string->shorten_url($raw_value)
            : $this->get_empty_char();
    }

    public function get_raw_value($id)
    {
        $comment = get_comment($id);

        return $comment->comment_author_url;
    }

}