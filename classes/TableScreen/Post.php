<?php

declare(strict_types=1);

namespace AC\TableScreen;

use AC;
use AC\Column;
use AC\ColumnRepository;
use AC\ListScreen\ListTable;
use AC\MetaType;
use AC\PostType;
use AC\Table;
use AC\TableScreen;
use AC\Type\Labels;
use AC\Type\ListKey;
use AC\Type\Uri;
use AC\Type\Url;
use AC\WpListTableFactory;
use WP_Post_Type;

class Post extends TableScreen implements PostType, ListTable
{

    private $post_type;

    public function __construct(WP_Post_Type $post_type, ListKey $key, string $screen_id)
    {
        parent::__construct($key, $screen_id, false);

        $this->post_type = $post_type;
    }

    public function list_table(): AC\ListTable
    {
        return new AC\ListTable\Post((new WpListTableFactory())->create_post_table($this->screen_id));
    }

    public function manage_value(ColumnRepository $column_repository): AC\Table\ManageValue
    {
        return new Table\ManageValue\Post($this->post_type->name, $column_repository);
    }

    public function get_post_type(): string
    {
        return $this->post_type->name;
    }

    public function get_group(): string
    {
        return 'post';
    }

    public function get_query_type(): string
    {
        return 'post';
    }

    public function get_meta_type(): MetaType
    {
        return new MetaType(MetaType::POST);
    }

    public function get_attr_id(): string
    {
        return '#the-list';
    }

    public function get_heading_hookname(): string
    {
        return sprintf('manage_%s_columns', $this->screen_id);
    }

    public function get_url(): Uri
    {
        return new Url\ListTable\Post($this->post_type->name);
    }

    public function get_labels(): Labels
    {
        return new Labels(
            $this->post_type->labels->name ?? '',
            $this->post_type->labels->singular_name ?? ''
        );
    }

    private function supports_template(): bool
    {
        return function_exists('get_page_templates') && get_page_templates(null, $this->post_type->name);
    }

    protected function get_columns_fqn(): array
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

        if (post_type_supports($this->post_type->name, 'post-formats')) {
            $columns[] = Column\Post\Formats::class;
        }
        if (post_type_supports($this->post_type->name, 'comments')) {
            $columns[] = Column\Post\Comments::class;
            $columns[] = Column\Post\CommentStatus::class;
            $columns[] = Column\Post\CommentCount::class;
            $columns[] = Column\Post\PingStatus::class;
        }
        if ($this->supports_template()) {
            $columns[] = Column\Post\PageTemplate::class;
        }
        if (post_type_supports($this->post_type->name, 'title')) {
            $columns[] = Column\Post\Title::class;
            $columns[] = Column\Post\TitleRaw::class;
        }
        if (post_type_supports($this->post_type->name, 'thumbnail')) {
            $columns[] = Column\Post\FeaturedImage::class;
        }
        if (get_object_taxonomies($this->post_type->name)) {
            $columns[] = Column\Post\Taxonomy::class;
        }
        if (is_object_in_taxonomy($this->post_type->name, 'category')) {
            $columns[] = Column\Post\Categories::class;
        }
        if (is_object_in_taxonomy($this->post_type->name, 'post_tag')) {
            $columns[] = Column\Post\Tags::class;
        }
        if (is_post_type_hierarchical($this->post_type->name)) {
            $columns[] = Column\Post\Depth::class;
            $columns[] = Column\Post\PostParent::class;
        }
        if (post_type_supports($this->post_type->name, 'editor')) {
            $columns[] = Column\Post\Content::class;
            $columns[] = Column\Post\EstimatedReadingTime::class;
            $columns[] = Column\Post\WordCount::class;
        }
        if (post_type_supports($this->post_type->name, 'thumbnail')) {
            $columns[] = Column\Post\FeaturedImage::class;
        }
        if (post_type_supports($this->post_type->name, 'post-formats')) {
            $columns[] = Column\Post\Formats::class;
        }
        if ('post' === $this->post_type->name) {
            $columns[] = Column\Post\Sticky::class;
        }

        // TODO check all column for conditionals 'is_valid'

        return $columns;
    }

}