<?php

declare(strict_types=1);

namespace AC\Acf\FieldGroup;

use AC\PostType;
use AC\TableScreen;

class QueryFactory
{

    public function create(TableScreen $table_screen): ?Query
    {
        if ($table_screen instanceof TableScreen\Media) {
            return new Location\Media();
        }

        if ($table_screen instanceof TableScreen\Comment) {
            return new Location\Comment();
        }

        if ($table_screen instanceof PostType) {
            return new Location\Post((string)$table_screen->get_post_type());
        }

        if ($table_screen instanceof TableScreen\User) {
            return new Location\User();
        }

        return null;
    }

}
