<?php

declare(strict_types=1);

namespace AC\Helper;

use AC\Formatter\Post\PostStatusIcon;
use AC\Type\Value;
use WP_Post;

class Post extends Creatable
{
    public function count_user_posts(int $user_id, ?array $post_types = null, ?array $post_stati = null): int
    {
        global $wpdb;

        $where = ['post_author = %d'];
        $params = [$user_id];

        if ($post_stati) {
            $where[] = sprintf('post_status IN (%s)', implode(',', array_fill(0, count($post_stati), '%s')));
            $params = array_merge($params, array_values($post_stati));
        }
        if ($post_types) {
            $where[] = sprintf('post_type IN (%s)', implode(',', array_fill(0, count($post_types), '%s')));
            $params = array_merge($params, array_values($post_types));
        }

        $sql = sprintf("SELECT COUNT(*) FROM $wpdb->posts WHERE %s", implode(' AND ', $where));

        return (int)$wpdb->get_var($wpdb->prepare($sql, $params));
    }

    /**
     * @deprecated 7.0.13 Use get_post_field( $field, $id, 'raw' ) instead.
     */
    public function get_raw_field(string $field, int $id): ?string
    {
        _deprecated_function(__METHOD__, '7.0.13', "get_post_field( \$field, \$id, 'raw' )");

        $value = get_post_field($field, $id, 'raw');

        return '' === $value ? null : $value;
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
