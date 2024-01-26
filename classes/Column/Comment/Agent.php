<?php

namespace AC\Column\Comment;

use AC\Column;

class Agent extends Column
{

    public function __construct()
    {
        $this->set_type('column-agent');
        $this->set_label(__('Agent', 'codepress-admin-columns'));
    }

    public function get_value($id)
    {
        return $this->get_raw_value($id);
    }

    public function get_raw_value($id)
    {
        $comment = get_comment($id);

        return $comment->comment_agent;
    }

}