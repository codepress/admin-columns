<?php

namespace AC\TableScreenFactory\ColumnTypes;

use WP_Post_Type;

interface PostFactoryInterface
{

    /**
     * @param WP_Post_Type $post_type
     *
     * @return string[]
     */
    public function create(WP_Post_Type $post_type): array;

}