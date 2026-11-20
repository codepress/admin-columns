<?php

declare(strict_types=1);

namespace AC\ColumnPreview\SampleItems;

use AC\ColumnPreview\SampleItems;
use AC\TableScreen;

class User implements SampleItems
{
    public function supports(TableScreen $table_screen): bool
    {
        return $table_screen instanceof TableScreen\User;
    }

    public function get_ids(TableScreen $table_screen, int $limit): array
    {
        return get_users([
            'number'  => $limit,
            'fields'  => 'ID',
            'orderby' => 'registered',
            'order'   => 'DESC',
        ]);
    }

    public function get_label(TableScreen $table_screen, int $id): string
    {
        $user = get_userdata($id);

        return $user ? $user->display_name : '';
    }

}
