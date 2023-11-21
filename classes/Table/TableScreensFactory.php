<?php

declare(strict_types=1);

namespace AC\Table;

use AC\PostTypeRepository;
use AC\TableScreenFactory;
use AC\Type\ListKey;

class TableScreensFactory implements TableScreensFactoryInterface
{

    protected $post_type_repository;

    protected $table_screen_factory;

    public function __construct(PostTypeRepository $post_type_repository, TableScreenFactory $table_screen_factory)
    {
        $this->post_type_repository = $post_type_repository;
        $this->table_screen_factory = $table_screen_factory;
    }

    public function create(): TableScreens
    {
        $keys = $this->post_type_repository->find_all();

        $keys[] = 'wp-comments';
        $keys[] = 'wp-users';
        $keys[] = 'wp-media';

        $collection = new TableScreens();

        foreach ($keys as $key) {
            if ($this->table_screen_factory->can_create(new ListKey($key))) {
                $collection->add($this->table_screen_factory->create(new ListKey($key)));
            }
        }

        do_action('ac/list_keys', $collection);

        return $collection;
    }

}