<?php

namespace AC\Column\Post;

use AC\Column;

class PostParent extends Column
{

    public function __construct()
    {
        $this->set_type('column-parent');
        $this->set_label(__('Parent', 'codepress-admin-columns'));
    }

    public function get_value($post_id)
    {
        $parent_id = $this->get_raw_value($post_id);

        if ( ! $parent_id) {
            return $this->get_empty_char();
        }

        return ac_helper()->html->link(
            get_edit_post_link($parent_id),
            ac_helper()->post->get_raw_field('post_title', $parent_id)
        );
    }

    public function get_raw_value($post_id)
    {
        $parent_id = ac_helper()->post->get_raw_field('post_parent', $post_id);

        return $parent_id && is_numeric($parent_id)
            ? (int)$parent_id
            : false;
    }

}