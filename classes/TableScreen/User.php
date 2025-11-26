<?php

declare(strict_types=1);

namespace AC\TableScreen;

use AC;
use AC\ListTableFactory;
use AC\TableScreen;
use AC\Type\Labels;
use AC\Type\TableId;
use AC\Type\Url;

class User extends TableScreen implements ListTable, MetaType, TotalItems
{

    use AC\ListTable\TotalItemsTrait;

    public function __construct()
    {
        parent::__construct(
            new TableId('wp-users'),
            'users',
            new Labels(
                __('User'),
                __('Users')
            ),
            new Url\ListTable('users.php')
        );
    }

    public function list_table(): AC\ListTable
    {
        return ListTableFactory::create_user($this->screen_id);
    }

    public function get_meta_type(): AC\MetaType
    {
        return AC\MetaType::create_user_meta();
    }

}