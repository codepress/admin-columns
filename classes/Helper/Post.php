<?php

namespace AC\Helper;

use AC\Formatter\Post\PostStatusIcon;
use AC\Type\Value;
use WP_Post;

class Post
{

    private function esc_sql_array(array $array): string
    {
        return sprintf("'%s'", implode("','", array_map('esc_sql', $array)));
    }

    public function count_user_posts(int $user_id, ?array $post_types = null, ?array $post_stati = null): int
    {
        global $wpdb;

        $where = [
            sprintf('post_author = %d', $user_id),
        ];

        if ($post_stati) {
            $where[] = sprintf('post_status IN (%s)', $this->esc_sql_array($post_stati));
        }
        if ($post_types) {
            $where[] = sprintf('post_type IN (%s)', $this->esc_sql_array($post_types));
        }

        $sql = sprintf("SELECT COUNT(*) FROM $wpdb->posts WHERE %s", implode(' AND ', $where));

        return (int)$wpdb->get_var($sql);
    }

    public function get_raw_field(string $field, int $id): ?string
    {
        global $wpdb;

        $sql = "
			SELECT " . $wpdb->_real_escape($field) . "
			FROM $wpdb->posts
			WHERE ID = %d
			LIMIT 1
		";

        return $wpdb->get_var($wpdb->prepare($sql, $id));
    }

    /*
     * @deprecated since 7.0.9
     */
    public function get_status_icon(WP_Post $post): ?string
    {
        _deprecated_function(__METHOD__, '7.0.9');

        return (string)(new PostStatusIcon())->format(new Value($post->ID, $post));
    }

    /*
     * @deprecated since 7.0.9
     */
    public function get_title(): string
    {
        _deprecated_function(__METHOD__, '7.0.9');

        return '';
    }

}