<?php

declare(strict_types=1);

namespace AC\Table\Service;

use AC\ColumnCollection;
use AC\Registerable;
use AC\Services;
use AC\Type\PostTypeSlug;

// TODO Proof-of-concept
class Posts implements Registerable
{

    private PostTypeSlug $post_type;

    private ColumnCollection $columns;

    public function __construct(PostTypeSlug $post_type, ColumnCollection $columns)
    {
        $this->post_type = $post_type;
        $this->columns = $columns;
    }

    public function register(): void
    {
        $services = new Services();

        foreach ($this->columns as $column) {
            $services->add(new Post($this->post_type, $column));
        }

        $services->register();
    }

}