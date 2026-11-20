<?php

declare(strict_types=1);

namespace AC\ColumnPreview\SampleItems;

use AC\ColumnPreview\SampleItems;
use AC\TableScreen;

class Media implements SampleItems
{
    public function supports(TableScreen $table_screen): bool
    {
        return $table_screen instanceof TableScreen\Media;
    }

    public function get_ids(TableScreen $table_screen, int $limit): array
    {
        return get_posts([
            'post_type'      => 'attachment',
            'post_status'    => 'inherit',
            'posts_per_page' => $limit,
            'fields'         => 'ids',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);
    }

    public function get_label(TableScreen $table_screen, int $id): string
    {
        return wp_strip_all_tags(get_the_title($id));
    }

}
