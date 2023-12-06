<?php

namespace AC\Helper;

use WP_Post;

class Post
{

    public function exists($id): bool
    {
        return $this->get_raw_field('ID', $id) ? true : false;
    }

    private function esc_sql_array($array): string
    {
        return sprintf("'%s'", implode("','", array_map('esc_sql', $array)));
    }

    public function count_user_posts(int $user_id, array $post_types, array $post_stati): int
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

    /**
     * @param int $post_id Post ID
     * @param int $words
     *
     * @return string Post Excerpt.
     * @since 1.0
     */
    public function excerpt($post_id, $words = 400)
    {
        global $post;

        $save_post = $post;
        $post = get_post($post_id);

        setup_postdata($post);

        $excerpt = get_the_excerpt();
        $post = $save_post;

        if ($post) {
            setup_postdata($post);
        }

        return ac_helper()->string->trim_words($excerpt, $words);
    }

    /**
     * @param string $field Field
     * @param int    $id    Post ID
     *
     * @return string|false
     */
    public function get_raw_field($field, $id)
    {
        global $wpdb;

        if ( ! $id || ! is_numeric($id)) {
            return false;
        }

        $sql = "
			SELECT " . $wpdb->_real_escape($field) . "
			FROM $wpdb->posts
			WHERE ID = %d
			LIMIT 1
		";

        return $wpdb->get_var($wpdb->prepare($sql, $id));
    }

    /**
     * Get Post Title or Media Filename
     *
     * @param int|WP_Post $post_id
     *
     * @return bool|string
     */
    public function get_title($post_id)
    {
        $post = get_post($post_id);

        if ( ! $post instanceof WP_Post) {
            return false;
        }

        $title = $post->post_title;

        if ('attachment' === $post->post_type) {
            $title = ac_helper()->image->get_file_name($post->ID);
        }

        return $title;
    }

    /**
     * @param WP_Post $post Post
     *
     * @return false|string Dash icon with tooltip
     */
    public function get_status_icon($post)
    {
        $icon = false;

        switch ($post->post_status) {
            case 'private' :
                $icon = ac_helper()->html->tooltip(
                    ac_helper()->icon->dashicon(['icon' => 'hidden', 'class' => 'gray']),
                    __('Private')
                );
                break;
            case 'publish' :
                $icon = ac_helper()->html->tooltip(
                    ac_helper()->icon->dashicon(['icon' => 'yes', 'class' => 'blue large']),
                    __('Published')
                );
                break;
            case 'draft' :
                $icon = ac_helper()->html->tooltip(
                    ac_helper()->icon->dashicon(['icon' => 'edit', 'class' => 'green']),
                    __('Draft')
                );
                break;
            case 'pending' :
                $icon = ac_helper()->html->tooltip(
                    ac_helper()->icon->dashicon(['icon' => 'backup', 'class' => 'orange']),
                    __('Pending for review')
                );
                break;
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
                break;
        }

        return $icon;
    }

}