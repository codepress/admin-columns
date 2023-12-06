<?php

namespace AC\ListScreen;

use AC;
use AC\Column;
use AC\ColumnRepository;
use AC\MetaType;
use AC\Type\Uri;
use AC\Type\Url;
use AC\WpListTableFactory;

class User extends AC\ListScreen implements ManageValue, ListTable
{

    public function __construct()
    {
        parent::__construct('wp-users', 'users');

        $this->group = 'user';
        $this->label = __('Users');
        $this->singular_label = __('User');
        $this->meta_type = MetaType::USER;
    }

    public function get_table_url(): Uri
    {
        return new Url\ListTable('users.php', $this->has_id() ? $this->get_id() : null);
    }

    public function list_table(): AC\ListTable
    {
        return new AC\ListTable\User((new WpListTableFactory())->create_user_table($this->get_screen_id()));
    }

    public function manage_value(): AC\Table\ManageValue
    {
        return new AC\Table\ManageValue\User(new ColumnRepository($this));
    }

    protected function register_column_types(): void
    {
        $this->register_column_types_from_list([
            Column\CustomField::class,
            Column\Actions::class,
            Column\User\CommentCount::class,
            Column\User\Description::class,
            Column\User\DisplayName::class,
            Column\User\Email::class,
            Column\User\FirstName::class,
            Column\User\FirstPost::class,
            Column\User\FullName::class,
            Column\User\ID::class,
            Column\User\LastName::class,
            Column\User\LastPost::class,
            Column\User\Login::class,
            Column\User\Name::class,
            Column\User\Nicename::class,
            Column\User\Nickname::class,
            Column\User\PostCount::class,
            Column\User\Posts::class,
            Column\User\Registered::class,
            Column\User\RichEditing::class,
            Column\User\Role::class,
            Column\User\ShowToolbar::class,
            Column\User\Url::class,
            Column\User\Username::class,
        ]);
    }

}