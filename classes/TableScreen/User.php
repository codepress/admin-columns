<?php

declare(strict_types=1);

namespace AC\TableScreen;

use AC;
use AC\ListScreen;
use AC\ListTableFactory;
use AC\Table;
use AC\Table\ColumnRenderable;
use AC\Table\ManageValue;
use AC\TableScreen;
use AC\Type\Labels;
use AC\Type\ListKey;
use AC\Type\Uri;
use AC\Type\Url;

class User extends TableScreen implements ListTable, MetaType
{

    public function __construct()
    {
        parent::__construct(new ListKey('wp-users'), 'users');
    }

    public function manage_value(ListScreen $list_screen): ManageValue
    {
        return new Table\ManageValue\User(new ColumnRenderable($list_screen));
    }

    public function list_table(): AC\ListTable
    {
        return ListTableFactory::create_user($this->screen_id);
    }

    public function get_query_type(): string
    {
        return 'user';
    }

    public function get_meta_type(): AC\MetaType
    {
        return AC\MetaType::create_user_type();
    }

    public function get_attr_id(): string
    {
        return '#the-list';
    }

    public function get_url(): Uri
    {
        return new Url\ListTable('users.php');
    }

    public function get_heading_hookname(): string
    {
        return sprintf('manage_%s_columns', $this->screen_id);
    }

    public function get_labels(): Labels
    {
        return new Labels(
            __('Users'),
            __('User')
        );
    }

}