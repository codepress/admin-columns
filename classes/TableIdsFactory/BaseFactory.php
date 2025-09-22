<?php

declare(strict_types=1);

namespace AC\TableIdsFactory;

use AC\PostTypeRepository;
use AC\TableIdsFactory;
use AC\Type\TableId;
use AC\Type\TableIdCollection;

class BaseFactory implements TableIdsFactory
{

    private PostTypeRepository $post_type_repository;

    public function __construct(PostTypeRepository $post_type_repository)
    {
        $this->post_type_repository = $post_type_repository;
    }

    public function create(): TableIdCollection
    {
        $keys = $this->post_type_repository->find_all();

        $keys[] = 'wp-comments';
        $keys[] = 'wp-users';
        $keys[] = 'wp-media';

        $collection = new TableIdCollection();

        foreach ($keys as $key) {
            $collection->add(new TableId($key));
        }

        return $collection;
    }

}