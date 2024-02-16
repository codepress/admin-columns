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

        $factories[] = $this->container->get(User\AuthorSlugFactory::class);
        $factories[] = $this->container->get(User\CommentCountFactory::class);
        $factories[] = $this->container->get(User\DescriptionFactory::class);
        $factories[] = $this->container->get(User\DisplayNameFactory::class);
        $factories[] = $this->container->get(User\FirstNameFactory::class);
        $factories[] = $this->container->get(User\FullNameFactory::class);
        $factories[] = $this->container->make(User\FirstPostFactory::class, [
            'post_type_factory' => new AC\Settings\Column\PostTypeFactory(true),
        ]);
        $factories[] = $this->container->get(User\LastNameFactory::class);
        $factories[] = $this->container->get(User\NicknameFactory::class);
        $factories[] = $this->container->get(User\RegisteredDateFactory::class);
        $factories[] = $this->container->get(User\RegisteredDateFactory::class);
        $factories[] = $this->container->get(User\UserNameFactory::class);
        $factories[] = $this->container->get(User\UserIdFactory::class);

        $collection = new Collection\ColumnFactories();

        foreach ($factories as $factory) {
            $collection->add($factory->get_type(), $factory);
        }

        return $collection;
    }

}