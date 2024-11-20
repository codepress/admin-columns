<?php

declare(strict_types=1);

namespace AC\ListKeysFactory;

use AC\ListKeyCollection;
use AC\ListKeysFactory;
use AC\PostTypeRepository;
use AC\Type\ListKey;

class BaseFactory implements ListKeysFactory
{

    private PostTypeRepository $post_type_repository;

    public function __construct(PostTypeRepository $post_type_repository)
    {
        $this->post_type_repository = $post_type_repository;
    }

    public function create(): ListKeyCollection
    {
        $keys = $this->post_type_repository->find_all();

        $keys[] = 'wp-comments';
        $keys[] = 'wp-users';
        $keys[] = 'wp-media';

        $collection = new ListKeyCollection();

        foreach ($keys as $key) {
            $collection->add(new ListKey($key));
        }

        return $collection;
    }

}