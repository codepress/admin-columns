<?php

declare(strict_types=1);

namespace AC\ApplyFilter;

class PostTypes
{

    public function apply_filters(array $post_types = []): array
    {
        return (array)apply_filters('ac/post_types', $post_types);
    }

}