<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC;
use AC\ColumnFactory;
use AC\ColumnFactory\User;
use AC\ColumnFactoryDefinitionCollection;
use AC\TableScreen;

final class UserFactory extends BaseFactory
{

    protected function get_factories(TableScreen $table_screen): ColumnFactoryDefinitionCollection
    {
        $collection = new ColumnFactoryDefinitionCollection();

        if ( ! $table_screen instanceof TableScreen\User) {
            return $collection;
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

        foreach ($factories as $factory => $parameters) {
            if (is_numeric($factory)) {
                $factory = $parameters;
                $parameters = [];
            }

            $collection->add(
                new AC\Type\ColumnFactoryDefinition(
                    $factory,
                    $parameters ?? []
                )
            );
        }

        return $collection;
    }

}