<?php

declare(strict_types=1);

namespace AC\TableScreenFactory;

use AC\Exception\InvalidTableScreenException;
use AC\PostTypeRepository;
use AC\TableScreen;
use AC\TableScreenFactory;
use AC\Type\TableId;
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
        $post_type = get_post_type_object((string)$screen->post_type);

        if (! $post_type instanceof WP_Post_Type) {
            throw InvalidTableScreenException::from_invalid_id(new TableId((string)$screen->post_type));
        }

        return $this->create_table_screen($post_type);
    }

    public function can_create(TableId $id): bool
    {
        return $this->post_type_repository->exists((string)$id);
    }

    public function create(TableId $id): TableScreen
    {
        if (! $this->can_create($id)) {
            throw InvalidTableScreenException::from_invalid_id($id);
        }

        $post_type = get_post_type_object((string)$id);

        if (! $post_type instanceof WP_Post_Type) {
            throw InvalidTableScreenException::from_invalid_id($id);
        }

        return $this->create_table_screen($post_type);
    }

    protected function create_table_screen(WP_Post_Type $post_type): TableScreen\Post
    {
        return new TableScreen\Post($post_type);
    }

}
