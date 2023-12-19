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

class User extends TableScreen implements TableScreen\ListTable, TableScreen\MetaType
{

    public function __construct(array $columns)
    {
        parent::__construct(new ListKey('wp-users'), 'users', $columns);
    }

    public function manage_value(ListScreen $list_screen): AC\Table\ManageValue
    {
        return new Table\ManageValue\User($list_screen);
    }

    public function list_table(): AC\ListTable
    {
        return new AC\ListTable\User((new WpListTableFactory())->create_user_table($this->screen_id));
    }

    public function get_query_type(): string
    {
        return 'user';
    }

    public function get_meta_type(): MetaType
    {
        return new MetaType(MetaType::USER);
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