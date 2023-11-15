<?php

declare(strict_types=1);

namespace AC\TableScreenFactory;

use AC\PostTypeRepository;
use AC\TableScreen;
use AC\TableScreenFactory;
use AC\Type\ListKey;
use WP_Post_Type;
use WP_Screen;

class Post implements TableScreenFactory
{

    private $post_type_repository;

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
        return $this->create_by_post_type(get_post_type_object($screen->post_type));
    }

    private function create_by_post_type(WP_Post_Type $post_type): TableScreen
    {
        return new TableScreen\Post(
            $post_type,
            new ListKey($post_type->name),
            sprintf('edit-%s', $post_type->name)
        );
    }

    public function can_create(ListKey $key): bool
    {
        return null !== get_post_type_object((string)$key);
    }

    public function create(ListKey $key): TableScreen
    {
        return $this->create_by_post_type(get_post_type_object((string)$key));
    }

}