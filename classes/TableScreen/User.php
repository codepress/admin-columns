<?php

declare(strict_types=1);

namespace AC\TableScreen;

use AC;
use AC\ListTableFactory;
use AC\TableScreen;
use AC\Type\Labels;
use AC\Type\ListKey;
use AC\Type\Uri;
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
            )
        );
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

}