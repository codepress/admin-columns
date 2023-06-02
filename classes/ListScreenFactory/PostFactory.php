<?php

declare(strict_types=1);

namespace AC\ListScreenFactory;

use AC\ListScreen;
use AC\ListScreen\Post;
use AC\PostTypeRepository;
use ACP\Bookmark\SegmentRepository;
use WP_Screen;

class PostFactory extends BaseFactory
{

    private $post_type_repository;

    public function __construct(PostTypeRepository $post_type_repository, SegmentRepository $segment_repository )
    {
        parent::__construct( $segment_repository );

        $this->post_type_repository = $post_type_repository;
    }

    protected function create_list_screen_from_wp_screen(WP_Screen $screen): ListScreen
    {
        return $this->create_list_screen($screen->post_type);
    }

    protected function create_list_screen(string $key): ListScreen
    {
        return new Post($key);
    }

    public function can_create(string $key): bool
    {
        return post_type_exists($key) && $this->is_supported_post_type($key);
    }

    private function is_supported_post_type(string $post_type): bool
    {
        return $this->post_type_repository->exists($post_type);
    }

    public function can_create_from_wp_screen(WP_Screen $screen): bool
    {
        return 'edit' === $screen->base &&
               $screen->post_type &&
               'edit-' . $screen->post_type === $screen->id &&
               $this->is_supported_post_type($screen->post_type);
    }

}