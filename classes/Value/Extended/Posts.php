<?php

declare(strict_types=1);

namespace AC\Value\Extended;

use AC\Value\ExtendedValueLink;
use AC\View;

class Posts implements ExtendedValue
{

    private array $post_types;

    private array $post_stati;

    public function __construct(array $post_types = null, array $post_stati = null)
    {
        $this->post_types = $post_types ?? get_post_types(['show_ui' => true]);
        $this->post_stati = $post_stati ?? get_post_stati(['internal' => 0]);
    }

    public function can_render(string $view): bool
    {
        return $view === 'posts';
    }

    public function get_link(int $id, string $label): ExtendedValueLink
    {
        return new ExtendedValueLink($label, $id, 'posts', ['class' => '-w-xlarge']);
    }

    private function get_post_count(int $user_id): int
    {
        return ac_helper()->post->count_user_posts(
            $user_id,
            $this->post_types,
            $this->post_stati
        );
    }

    public function render(int $id, array $params): string
    {
        $count = $this->get_post_count($id);

        if ($count < 1) {
            return __('No items', 'codepress-admin-columns');
        }

        $posts = [];

        $limit = 30;

        foreach ($this->get_recent_posts($id, $limit) as $post) {
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
            'post_types' => $this->get_post_count_per_post_type($id),
        ]);

        return $view->set_template('modal-value/posts')
                    ->render();
    }

    private function get_post_count_per_post_type(int $user_id): array
    {
        $post_types = [];

        foreach ($this->post_types as $post_type) {
            $count = ac_helper()->post->count_user_posts($user_id, [$post_type], $this->post_stati);

            if ($count > 0) {
                $post_types[] = [
                    'link'      => $this->get_post_table_link($user_id, $post_type),
                    'post_type' => get_post_type_object($post_type)->labels->singular_name ?? $post_type,
                    'count'     => number_format_i18n($count),
                ];
            }
        }

        return $post_types;
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

    private function get_recent_posts(int $user_id, int $limit = null): array
    {
        return get_posts([
            'author'         => $user_id,
            'post_type'      => $this->post_types,
            'posts_per_page' => $limit ?: -1,
            'post_status'    => $this->post_stati,
        ]);
    }

}