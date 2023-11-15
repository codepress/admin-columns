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
            Column\Post\Categories::class,
            Column\Post\CommentCount::class,
            Column\Post\Comments::class,
            Column\Post\CommentStatus::class,
            Column\Post\Content::class,
            Column\Post\Date::class,
            Column\Post\DatePublished::class,
            Column\Post\Depth::class,
            Column\Post\EstimatedReadingTime::class,
            Column\Post\Excerpt::class,
            Column\Post\FeaturedImage::class,
            Column\Post\Formats::class,
            Column\Post\ID::class,
            Column\Post\LastModifiedAuthor::class,
            Column\Post\Menu::class,
            Column\Post\Modified::class,
            Column\Post\Order::class,
            Column\Post\PasswordProtected::class,
            Column\Post\Path::class,
            Column\Post\Permalink::class,
            Column\Post\PingStatus::class,
            Column\Post\PostParent::class,
            Column\Post\Shortcodes::class,
            Column\Post\Shortlink::class,
            Column\Post\Slug::class,
            Column\Post\Status::class,
            Column\Post\Sticky::class,
            Column\Post\Tags::class,
            Column\Post\Taxonomy::class,
            Column\Post\Title::class,
            Column\Post\TitleRaw::class,
            Column\Post\WordCount::class,
        ];

        if ($this->supports_template()) {
            $columns[] = Column\Post\PageTemplate::class;
        }

        if (post_type_supports($this->get_post_type(), 'thumbnail')) {
            $columns[] = Column\Post\FeaturedImage::class;
        }

        // TODO check all column for conditionals 'is_valid'

        return $columns;
    }

}