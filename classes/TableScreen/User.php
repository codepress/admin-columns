<?php

declare(strict_types=1);

namespace AC\TableScreen;

use AC;
use AC\ListTableFactory;
use AC\TableScreen;
use AC\Type\Labels;
use AC\Type\ListKey;
use AC\Type\Url;

class User extends TableScreen implements ListTable, MetaType
{

    public function __construct()
    {
        parent::__construct(
            new ListKey('wp-users'),
            'users',
            new Labels(
                __('Users'),
                __('User')
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
        return AC\MetaType::create_user_type();
    }

}