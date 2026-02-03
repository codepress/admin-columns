<?php

namespace AC\Helper;

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

        return $wpdb->get_var($sql);
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

    public function get_status_icon(WP_Post $post): ?string
    {
        switch ($post->post_status) {
            case 'private' :
                return ac_helper()->html->tooltip(
                    ac_helper()->icon->dashicon(['icon' => 'hidden', 'class' => 'gray']),
                    __('Private')
                );

            case 'publish' :
                return ac_helper()->html->tooltip(
                    ac_helper()->icon->dashicon(['icon' => 'yes', 'class' => 'blue large']),
                    __('Published')
                );

            case 'draft' :
                return ac_helper()->html->tooltip(
                    ac_helper()->icon->dashicon(['icon' => 'edit', 'class' => 'green']),
                    __('Draft')
                );

            case 'pending' :
                return ac_helper()->html->tooltip(
                    ac_helper()->icon->dashicon(['icon' => 'backup', 'class' => 'orange']),
                    __('Pending for review')
                );

            case 'future' :
                $icon = ac_helper()->html->tooltip(
                    ac_helper()->icon->dashicon(['icon' => 'clock']),
                    __('Scheduled') . ': <em>' . ac_helper()->date->date($post->post_date, 'wp_date_time') . '</em>'
                );

                // Missed schedule
                if ((time() - mysql2date('G', $post->post_date_gmt)) > 0) {
                    $icon .= ac_helper()->html->tooltip(
                        ac_helper()->icon->dashicon(['icon' => 'flag', 'class' => 'gray']),
                        __('Missed schedule')
                    );
                }

                return $icon;
            default:
                return null;
        }
    }

    public function get_title(int $post_id): string
    {
        _deprecated_function(__METHOD__, '7.0.9');

        return '';
    }

}