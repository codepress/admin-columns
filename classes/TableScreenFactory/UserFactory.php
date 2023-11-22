<?php

declare(strict_types=1);

namespace AC\TableScreenFactory;

use AC\Column;
use AC\TableScreen;
use AC\TableScreenFactory;
use AC\Type\ListKey;
use WP_Screen;

class UserFactory implements TableScreenFactory
{

    public function create(ListKey $key): TableScreen
    {
        return $this->create_table_screen();
    }

    public function can_create(ListKey $key): bool
    {
        return $key->equals(new ListKey('wp-users'));
    }

    public function create_from_wp_screen(WP_Screen $screen): TableScreen
    {
        return $this->create(new ListKey('wp-users'));
    }

    public function can_create_from_wp_screen(WP_Screen $screen): bool
    {
        return 'users' === $screen->base && 'users' === $screen->id && 'delete' !== filter_input(INPUT_GET, 'action');
    }

    protected function create_table_screen(): TableScreen\User
    {
        return new TableScreen\User(
            [
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
            ]
        );
    }

}