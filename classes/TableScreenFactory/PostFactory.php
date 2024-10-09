<?php

declare(strict_types=1);

namespace AC\TableScreenFactory;

use AC\Exception\InvalidListScreenException;
use AC\PostTypeRepository;
use AC\TableScreen;
use AC\TableScreenFactory;
use AC\Type\ListKey;
use WP_Post_Type;
use WP_Screen;

class PostFactory implements TableScreenFactory
{

    protected PostTypeRepository $post_type_repository;

    public function __construct(PostTypeRepository $post_type_repository)
    {
        $this->post_type_repository = $post_type_repository;
    }

    public function can_create_from_wp_screen(WP_Screen $screen): bool
    {
        return 'edit' === $screen->base &&
               $screen->post_type &&
               'edit-' . $screen->post_type === $screen->id &&
               $this->post_type_repository->exists($screen->post_type);
    }

    public function create_from_wp_screen(WP_Screen $screen): TableScreen
    {
        return $this->create_table_screen(get_post_type_object($screen->post_type));
    }

    public function can_create(ListKey $key): bool
    {
        return null !== get_post_type_object((string)$key);
    }

    public function create(ListKey $key): TableScreen
    {
        if ( ! $this->can_create($key)) {
            throw InvalidListScreenException::from_invalid_key($key);
        }

        return $this->create_table_screen(get_post_type_object((string)$key));
    }

    protected function create_table_screen(WP_Post_Type $post_type): TableScreen\Post
    {
        return new TableScreen\Post($post_type);
    }

}