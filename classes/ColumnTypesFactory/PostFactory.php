<?php

declare(strict_types=1);

namespace AC\ColumnTypesFactory;

use AC;
use AC\Column;
use AC\ColumnTypeCollection;
use AC\MetaType;
use AC\TableScreen;
use WP_Post_Type;

class PostFactory implements AC\ColumnTypesFactory
{

    public function create(TableScreen $table_screen): ?ColumnTypeCollection
    {
        if ( ! $table_screen instanceof AC\PostType) {
            return null;
        }

        return $this->get_columns(
            get_post_type_object($table_screen->get_post_type())
        );
    }

    private function get_columns(WP_Post_Type $post_type): ColumnTypeCollection
    {
        $columns = [
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

        $columns = ColumnTypeCollection::from_list($columns);

        $columns->add(new Column\CustomField(new MetaType(MetaType::POST)));

        return $columns;
    }

    protected function supports_template(string $post_type): bool
    {
        return function_exists('get_page_templates') && get_page_templates(null, $post_type);
    }

}