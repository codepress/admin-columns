<?php

declare(strict_types=1);

namespace AC\Table;

use AC\PostTypeRepository;
use AC\TableScreenFactory\Aggregate;
use AC\Type\ListKey;

class ListKeysFactory implements ListKeysFactoryInterface
{

    private $post_type_repository;

    private $table_screen_factory;

    public function __construct(PostTypeRepository $post_type_repository, Aggregate $table_screen_factory)
    {
        $this->post_type_repository = $post_type_repository;
        $this->table_screen_factory = $table_screen_factory;
    }

    public function create(): ListKeyCollection
    {
        $keys = $this->post_type_repository->find_all();

        $keys[] = 'wp-comments';
        $keys[] = 'wp-users';
        $keys[] = 'wp-media';

        $list_keys = array_map(static function (string $key): ListKey {
            return new ListKey($key);
        }, $keys);

        $list_keys = array_filter($list_keys, [$this->table_screen_factory, 'can_create']);

        $collection = new ListKeyCollection($list_keys);

        do_action('ac/list_keys', $collection);

        return $collection;
    }

    private function create_key(string $key): ListKey
    {
        return new ListKey($key);
    }

}