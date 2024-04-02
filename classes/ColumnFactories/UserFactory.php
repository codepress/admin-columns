<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC;
use AC\Collection;
use AC\ColumnFactories;
use AC\ColumnFactory\User;
use AC\TableScreen;
use AC\Vendor\DI\Container;

class UserFactory implements ColumnFactories
{

    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function create(TableScreen $table_screen): ?Collection\ColumnFactories
    {
        if ( ! $table_screen instanceof AC\TableScreen\User) {
            return null;
        }
        //
        //        $factories[] = $this->container->make(
        //            CustomFieldFactory::class,
        //            [
        //                'custom_field_factory' => new Settings\Column\CustomFieldFactory(
        //                    new MetaType(MetaType::POST),
        //                    $this->container->get(CustomFieldTypeFactory::class)
        //                ),
        //            ]
        //        );

        $factories[] = $this->container->make(User\AuthorSlugFactory::class);
        $factories[] = $this->container->make(User\CommentCountFactory::class);
        $factories[] = $this->container->make(User\DescriptionFactory::class);
        $factories[] = $this->container->make(User\DisplayNameFactory::class);
        $factories[] = $this->container->make(User\FirstNameFactory::class);
        $factories[] = $this->container->make(User\FullNameFactory::class);
        $factories[] = $this->container->make(User\FirstPostFactory::class, [
            'post_type' => new AC\Setting\ComponentFactory\PostType(true),
        ]);
        $factories[] = $this->container->make(User\LastNameFactory::class);
        $factories[] = $this->container->make(User\LastPostFactory::class, [
            'post_type' => new AC\Setting\ComponentFactory\PostType(true),
        ]);
        $factories[] = $this->container->make(User\NicknameFactory::class);
        $factories[] = $this->container->make(User\PostCountFactory::class, [
            'post_type' => new AC\Setting\ComponentFactory\PostType(true),
        ]);
        $factories[] = $this->container->make(User\RegisteredDateFactory::class);
        $factories[] = $this->container->make(User\VisualEditingFactory::class);
        $factories[] = $this->container->make(User\ShowToolbarFactory::class);
        $factories[] = $this->container->make(User\UserNameFactory::class);
        $factories[] = $this->container->make(User\UserIdFactory::class);
        $factories[] = $this->container->make(User\UserUrlFactory::class);

        $collection = new Collection\ColumnFactories();

        foreach ($factories as $factory) {
            $collection->add($factory->get_column_type(), $factory);
        }

        return $collection;
    }

}