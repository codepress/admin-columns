<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC;
use AC\ColumnFactory;
use AC\ColumnFactory\User;
use AC\ColumnFactoryDefinitionCollection;
use AC\TableScreen;

class UserFactory extends BaseFactory
{

    protected function get_factories(TableScreen $table_screen): ColumnFactoryDefinitionCollection
    {
        return new ColumnFactoryDefinitionCollection();
    }

    protected function get_fadctories(TableScreen $table_screen): array
    {
        if ( ! $table_screen instanceof AC\TableScreen\User) {
            return [];
        }

        $factories = [
            ColumnFactory\ActionsFactory::class,
            ColumnFactory\ActionsFactory::class,
            User\AuthorSlugFactory::class,
            User\CommentCountFactory::class,
            User\DescriptionFactory::class,
            User\DisplayNameFactory::class,
            User\FirstNameFactory::class,
            User\FullNameFactory::class,
            User\FirstPostFactory::class => [
                'post_type' => new AC\Setting\ComponentFactory\PostType(true),
            ],
            User\LastNameFactory::class,
            User\LastPostFactory::class  => [
                'post_type' => new AC\Setting\ComponentFactory\PostType(true),
            ],
            User\NicknameFactory::class,
            User\PostCountFactory::class => [
                'post_type' => new AC\Setting\ComponentFactory\PostType(true),
            ],
            User\RegisteredDateFactory::class,
            User\VisualEditingFactory::class,
            User\ShowToolbarFactory::class,
            User\UserNameFactory::class,
            User\UserIdFactory::class,
            User\UserUrlFactory::class,
        ];

        return $factories;
    }

}