<?php

declare(strict_types=1);

namespace AC\TableScreen;

use AC;
use AC\Column;
use AC\ColumnRepository;
use AC\MetaType;
use AC\Table;
use AC\TableScreen;
use AC\Type\Labels;
use AC\Type\ListKey;
use AC\Type\Uri;
use AC\Type\Url;
use AC\WpListTableFactory;

class Comment extends TableScreen implements AC\ListScreen\ListTable
{

    public function __construct(ListKey $key, string $screen_id)
    {
        parent::__construct($key, $screen_id, false);
    }

    public function get_heading_hookname(): string
    {
        return sprintf('manage_%s_columns', $this->screen_id);
    }

    public function manage_value(ColumnRepository $column_repository): AC\Table\ManageValue
    {
        return new Table\ManageValue\Comment($column_repository);
    }

    public function list_table(): AC\ListTable
    {
        return new AC\ListTable\Comment((new WpListTableFactory())->create_comment_table($this->screen_id));
    }

    public function get_group(): string
    {
        return 'comment';
    }

    public function get_query_type(): string
    {
        return 'comment';
    }

    public function get_meta_type(): MetaType
    {
        return new MetaType(MetaType::COMMENT);
    }

    public function get_attr_id(): string
    {
        return '#the-comment-list';
    }

    public function get_url(): Uri
    {
        return new Url\ListTable('edit-comments.php');
    }

    public function get_labels(): Labels
    {
        return new Labels(
            __('Comments'),
            __('Comment')
        );
    }

    protected function get_columns_fqn(): array
    {
        return [
            AC\Column\CustomField::class,
            AC\Column\Actions::class,
            Column\Comment\Agent::class,
            Column\Comment\Approved::class,
            Column\Comment\Author::class,
            Column\Comment\AuthorAvatar::class,
            Column\Comment\AuthorEmail::class,
            Column\Comment\AuthorIP::class,
            Column\Comment\AuthorName::class,
            Column\Comment\AuthorUrl::class,
            Column\Comment\Comment::class,
            Column\Comment\Date::class,
            Column\Comment\DateGmt::class,
            Column\Comment\Excerpt::class,
            Column\Comment\ID::class,
            Column\Comment\Post::class,
            Column\Comment\ReplyTo::class,
            Column\Comment\Response::class,
            Column\Comment\Status::class,
            Column\Comment\Type::class,
            Column\Comment\User::class,
            Column\Comment\WordCount::class,
        ];
    }

}