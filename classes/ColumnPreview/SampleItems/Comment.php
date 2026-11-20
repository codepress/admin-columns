<?php

declare(strict_types=1);

namespace AC\ColumnPreview\SampleItems;

use AC\ColumnPreview\SampleItems;
use AC\TableScreen;
use AC\Type\Value;

class Comment implements SampleItems
{
    public function supports(TableScreen $table_screen): bool
    {
        return $table_screen instanceof TableScreen\Comment;
    }

    public function get_values(TableScreen $table_screen, int $limit): array
    {
        return array_map(static function ($id): Value {
            return new Value((int)$id);
        }, $this->get_ids($table_screen, $limit));
    }

    private function get_ids(TableScreen $table_screen, int $limit): array
    {
        return get_comments([
            'number'  => $limit,
            'fields'  => 'ids',
            'orderby' => 'comment_date_gmt',
            'order'   => 'DESC',
        ]);
    }

    public function get_label(TableScreen $table_screen, $id): string
    {
        $comment = get_comment($id);

        return $comment ? $comment->comment_author : '';
    }

}
