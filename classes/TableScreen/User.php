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

class User extends TableScreen implements AC\ListScreen\ListTable
{

    public function __construct(ListKey $key)
    {
        parent::__construct($key, 'users', false);
    }

    public function manage_value(ColumnRepository $column_repository): AC\Table\ManageValue
    {
        return new Table\ManageValue\User($column_repository);
    }

    public function list_table(): AC\ListTable
    {
        return new AC\ListTable\User((new WpListTableFactory())->create_user_table($this->screen_id));
    }

    public function get_group(): string
    {
        return 'user';
    }

    public function get_query_type(): string
    {
        return MetaType::USER;
    }

    public function get_meta_type(): MetaType
    {
        return new MetaType(MetaType::USER);
    }

    public function get_attr_id(): string
    {
        // TODO
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

    protected function get_columns_fqn(): array
    {
        return [
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
        ];
    }

}