<?php

namespace AC\Column\Post;

use AC\Column;
use AC\Settings;

/**
 * Column displaying the number of comments for an item, displaying either the total
 * amount of comments, or the amount per status (e.g. "Approved", "Pending").
 * @since 2.0
 */
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

    public function is_valid()
    {
        return post_type_supports($this->get_post_type(), 'comments');
    }

    public function register_settings()
    {
        $this->add_setting(new Settings\Column\CommentCount($this));
    }

}