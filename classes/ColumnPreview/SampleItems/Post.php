<?php

declare(strict_types=1);

namespace AC\ColumnPreview\SampleItems;

use AC\ColumnPreview\SampleItems;
use AC\PostType;
use AC\TableScreen;
use AC\Type\Value;

class Post implements SampleItems
{
    public function supports(TableScreen $table_screen): bool
    {
        return $table_screen instanceof PostType;
    }

    public function get_values(TableScreen $table_screen, int $limit): array
    {
        return array_map(static function ($id): Value {
            return new Value((int)$id);
        }, $this->get_ids($table_screen, $limit));
    }

    private function get_ids(TableScreen $table_screen, int $limit): array
    {
        if (! $table_screen instanceof PostType) {
            return [];
        }

        return get_posts([
            'post_type'      => (string)$table_screen->get_post_type(),
            'post_status'    => 'any',
            'posts_per_page' => $limit,
            'fields'         => 'ids',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);
    }

    public function get_label(TableScreen $table_screen, $id): string
    {
        return wp_strip_all_tags(get_the_title($id));
    }

}
