<?php

declare(strict_types=1);

namespace AC\TableScreen;

use AC;
use AC\ListScreen;
use AC\MetaType;
use AC\Table;
use AC\TableScreen;
use AC\Type\Labels;
use AC\Type\ListKey;
use AC\Type\Uri;
use AC\Type\Url;
use AC\WpListTableFactory;

class Comment extends TableScreen implements ListTable, TableScreen\MetaType
{

    public function __construct(array $columns)
    {
        parent::__construct(new ListKey('wp-comments'), 'edit-comments', $columns);
    }

    public function get_heading_hookname(): string
    {
        return sprintf('manage_%s_columns', $this->screen_id);
    }

    public function manage_value(ListScreen $list_screen): AC\Table\ManageValue
    {
        return new Table\ManageValue\Comment($list_screen);
    }

    public function list_table(): AC\ListTable
    {
        return new AC\ListTable\Comment((new WpListTableFactory())->create_comment_table($this->screen_id));
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

}