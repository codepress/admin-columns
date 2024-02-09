<?php

namespace AC\Column\Post;

use AC\Column;
use AC\Settings;

// TODO remove
class CommentCount extends Column
{

    public function __construct()
    {
        $this->set_type('column-comment_count');
        $this->set_label(__('Comment Count', 'codepress-admin-columns'));
    }

    public function get_value($id)
    {
        $count = $this->get_comment_count($id);

        if ( ! $count) {
            return $this->get_empty_char();
        }

        // TODO
        return ac_helper()->html->link(
            add_query_arg([
                'p'              => $id,
                'comment_status' => $this->get_comment_status(),
            ], admin_url('edit-comments.php')),
            $count
        );
    }

    public function get_comment_status(): string
    {
        return $this->get_option(Settings\Column\CommentCount::NAME) ?: Settings\Column\CommentCount::STATUS_ALL;
    }

    public function get_comment_count($post_id): ?int
    {
        $count = wp_count_comments($post_id);
        $status = $this->get_comment_status();

        return empty($count->{$status})
            ? null
            : (int)$count->{$status};
    }

    public function register_settings()
    {
        $this->add_setting(new Settings\Column\CommentCount());
    }

}