<?php

declare(strict_types=1);

namespace AC\TableScreenFactory;

use AC\Column;
use AC\Exception\InvalidListScreenException;
use AC\PostTypeRepository;
use AC\TableScreen;
use AC\TableScreenFactory;
use AC\Type\ListKey;
use WP_Post_Type;
use WP_Screen;

class PostFactory implements TableScreenFactory
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
        return $this->create_table_screen(get_post_type_object($screen->post_type));
    }

    public function can_create(ListKey $key): bool
    {
        return null !== get_post_type_object((string)$key);
    }

    public function create(ListKey $key): TableScreen
    {
        if ( ! $this->can_create($key)) {
            // TODO 
            throw InvalidListScreenException::from_invalid_key($key);
        }

        return $this->create_table_screen(get_post_type_object((string)$key));
    }

    protected function get_columns(WP_Post_Type $post_type): array
    {
        $columns = [
            Column\CustomField::class,
            Column\Actions::class,
            Column\Post\Attachment::class,
            Column\Post\Author::class,
            Column\Post\AuthorName::class,
            Column\Post\BeforeMoreTag::class,
            Column\Post\Date::class,
            Column\Post\DatePublished::class,
            Column\Post\Excerpt::class,
            Column\Post\ID::class,
            Column\Post\LastModifiedAuthor::class,
            Column\Post\Menu::class,
            Column\Post\Modified::class,
            Column\Post\Order::class,
            Column\Post\PasswordProtected::class,
            Column\Post\Path::class,
            Column\Post\Permalink::class,
            Column\Post\Shortcodes::class,
            Column\Post\Shortlink::class,
            Column\Post\Slug::class,
            Column\Post\Status::class,
        ];

        if (post_type_supports($post_type->name, 'post-formats')) {
            $columns[] = Column\Post\Formats::class;
        }
        if (post_type_supports($post_type->name, 'comments')) {
            $columns[] = Column\Post\Comments::class;
            $columns[] = Column\Post\CommentStatus::class;
            $columns[] = Column\Post\CommentCount::class;
            $columns[] = Column\Post\PingStatus::class;
        }
        if ($this->supports_template($post_type->name)) {
            $columns[] = Column\Post\PageTemplate::class;
        }
        if (post_type_supports($post_type->name, 'title')) {
            $columns[] = Column\Post\Title::class;
            $columns[] = Column\Post\TitleRaw::class;
        }
        if (post_type_supports($post_type->name, 'thumbnail')) {
            $columns[] = Column\Post\FeaturedImage::class;
        }
        if (get_object_taxonomies($post_type->name)) {
            $columns[] = Column\Post\Taxonomy::class;
        }
        if (is_object_in_taxonomy($post_type->name, 'category')) {
            $columns[] = Column\Post\Categories::class;
        }
        if (is_object_in_taxonomy($post_type->name, 'post_tag')) {
            $columns[] = Column\Post\Tags::class;
        }
        if (is_post_type_hierarchical($post_type->name)) {
            $columns[] = Column\Post\Depth::class;
            $columns[] = Column\Post\PostParent::class;
        }
        if (post_type_supports($post_type->name, 'editor')) {
            $columns[] = Column\Post\Content::class;
            $columns[] = Column\Post\EstimatedReadingTime::class;
            $columns[] = Column\Post\WordCount::class;
        }
        if (post_type_supports($post_type->name, 'thumbnail')) {
            $columns[] = Column\Post\FeaturedImage::class;
        }
        if (post_type_supports($post_type->name, 'post-formats')) {
            $columns[] = Column\Post\Formats::class;
        }
        if ('post' === $post_type->name) {
            $columns[] = Column\Post\Sticky::class;
        }

        return $columns;
    }

    protected function create_table_screen(WP_Post_Type $post_type): TableScreen\Post
    {
        return new TableScreen\Post($post_type, $this->get_columns($post_type));
    }

    protected function supports_template(string $post_type): bool
    {
        return function_exists('get_page_templates') && get_page_templates(null, $post_type);
    }

}