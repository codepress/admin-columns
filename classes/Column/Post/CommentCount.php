<?php

namespace AC\Column\Post;

use AC\Column;
use AC\Settings;

class CommentCount extends Column
{

    public function __construct()
    {
        $this->set_type('column-comment_count');
        $this->set_label(__('Comment Count', 'codepress-admin-columns'));
    }

    public function get_value($id)
    {
        return $this->get_formatted_value($id);
    }

    public function register_settings()
    {
        $this->add_setting(new Settings\Column\CommentCount($this));
    }

    public function get_setting_comment_count(): ?Settings\Column\CommentCount
    {
        $setting = $this->get_setting('comment_count');

        return $setting instanceof Settings\Column\CommentCount
            ? $setting
            : null;
    }

}