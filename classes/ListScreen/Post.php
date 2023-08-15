<?php

namespace AC\ListScreen;

use AC;
use AC\Column;
use AC\ColumnRepository;
use AC\ListScreenPost;
use AC\Table;
use AC\Type\Uri;
use AC\Type\Url;
use AC\WpListTableFactory;

class Post extends ListScreenPost implements ManageValue, ListTable
{

    public function __construct(string $post_type, string $key = null, string $screen_id = null)
    {
        if (null === $key) {
            $key = $post_type;
        }

        if (null === $screen_id) {
            $screen_id = 'edit-' . $post_type;
        }
        parent::__construct($post_type, $key, $screen_id);

        $this->group = 'post';
    }

    public function list_table(): AC\ListTable
    {
        return new AC\ListTable\Post((new WpListTableFactory())->create_post_table($this->get_screen_id()));
    }

    public function manage_value(): Table\ManageValue
    {
        return new Table\ManageValue\Post($this->post_type, new ColumnRepository($this));
    }

    public function get_table_url(): Uri
    {
        return new Url\ListTable\Post($this->post_type, $this->has_id() ? $this->get_id() : null);
    }

    protected function get_post_type_label_var(string $var): ?string
    {
        $post_type_object = get_post_type_object($this->get_post_type());

        return $post_type_object->labels->{$var} ?? null;
    }

    public function get_label(): ?string
    {
        return $this->get_post_type_label_var('name');
    }

    public function get_singular_label(): ?string
    {
        return $this->get_post_type_label_var('singular_name');
    }

    protected function register_column_types(): void
    {
        parent::register_column_types();

        $this->register_column_types_from_list([
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
            Column\Post\PageTemplate::class,
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
        ]);
    }

}