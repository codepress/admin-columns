<?php

declare(strict_types=1);

namespace AC\Value\Extended;

use AC\Column;
use AC\ListScreen;
use AC\Value\ExtendedValueLink;
use AC\View;

class Posts implements ExtendedValue
{

    public function can_render(string $view): bool
    {
        return $view === 'posts';
    }

    public function get_link($id, string $label): ExtendedValueLink
    {
        return new ExtendedValueLink($label, $id, 'posts', ['class' => '-w-xlarge']);
    }

    private function get_post_count(int $user_id, array $post_types, array $status): int
    {
        return ac_helper()->post->count_user_posts(
            $user_id,
            $post_types,
            $status
        );
    }

    public function render($id, array $params, Column $column, ListScreen $list_screen): string
    {
        $post_types = $params['post_type'] ?? get_post_types(['show_ui' => true]);
        $status = $params['post_stati'] ?? get_post_stati(['internal' => 0]);

        $count = $this->get_post_count($id, $post_types, $status);

        if ($count < 1) {
            return __('No items', 'codepress-admin-columns');
        }

        $posts = [];

        $limit = 50;

        foreach ($this->get_recent_posts($id, $post_types, $status, $limit) as $post) {
            $post_title = strip_tags($post->post_title) ?: $post->ID;
            $edit_link = get_edit_post_link($post->ID);

            if ($edit_link) {
                $post_title = sprintf(
                    '<a href="%s">%s</a>',
                    $edit_link,
                    $post_title
                );
            }

            $post_type = get_post_type_object($post->post_type);

            if ($post_type) {
                $post_type = $post_type->labels->singular_name;
            }

            $posts[] = [
                'id'          => $post->ID,
                'post_type'   => $post_type,
                'post_title'  => $post_title,
                'post_status' => get_post_status_object($post->post_status)->label ?? '-',
                'post_date'   => ac_helper()->date->date($post->post_date),
            ];
        }

        $view = new View([
            'title'      => __('Recent items', 'codepress-admin-columns'),
            'posts'      => $posts,
            'post_types' => $this->get_post_count_per_post_type($id, $post_types, $status),
        ]);

        return $view->set_template('modal-value/posts')
                    ->render();
    }

    private function get_post_count_per_post_type(int $user_id, array $post_types, array $status): array
    {
        $items = [];

        foreach ($post_types as $post_type) {
            $count = ac_helper()->post->count_user_posts($user_id, [$post_type], $status);

            if ($count > 0) {
                $items[] = [
                    'link'      => $this->get_post_table_link($user_id, $post_type),
                    'post_type' => get_post_type_object($post_type)->labels->singular_name ?? $post_type,
                    'count'     => number_format_i18n($count),
                ];
            }
        }

        return $items;
    }

    private function get_post_table_link(int $user_id, string $post_type): string
    {
        return add_query_arg(
            [
                'post_type' => $post_type,
                'author'    => $user_id,
            ],
            admin_url('edit.php')
        );
    }

    private function get_recent_posts(int $user_id, array $post_types, array $post_status, ?int $limit = null): array
    {
        return get_posts([
            'author'         => $user_id,
            'post_type'      => $post_types,
            'post_status'    => $post_status,
            'posts_per_page' => $limit ?: -1,
        ]);
    }

}