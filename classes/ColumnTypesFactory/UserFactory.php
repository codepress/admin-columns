<?php

declare(strict_types=1);

namespace AC\ColumnTypesFactory;

use AC;
use AC\Column;
use AC\ColumnTypeCollection;
use AC\MetaType;
use AC\TableScreen;

class UserFactory implements AC\ColumnTypesFactory
{

    public function create(TableScreen $table_screen): ?ColumnTypeCollection
    {
        if ( ! $table_screen->get_key()->equals(new AC\Type\ListKey('wp-users'))) {
            return null;
        }

        return $this->get_columns();
    }

    private function get_columns(): ColumnTypeCollection
    {
        $collection = ColumnTypeCollection::from_list([
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

        $collection->add(
            new Column\CustomField(new MetaType(MetaType::USER))
        );

        return $collection;
    }

}