<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC;
use AC\Collection;
use AC\ColumnFactories;
use AC\ColumnFactory\CustomFieldFactory;
use AC\ColumnFactory\Post\AttachmentFactory;
use AC\ColumnFactory\Post\ExcerptFactory;
use AC\MetaType;
use AC\TableScreen;
use AC\Vendor\DI\Container;

class PostFactory implements ColumnFactories
{

    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function create(TableScreen $table_screen): ?Collection\ColumnFactories
    {
        if ( ! $table_screen instanceof AC\PostType) {
            return null;
        }

        $factories = [
            new AttachmentFactory()
        ];

        if (post_type_supports($table_screen->get_post_type(), 'excerpt')) {
            $factories[] = new ExcerptFactory();
        }

        $factories[] = new CustomFieldFactory(new MetaType(MetaType::POST), $this->container);

        return new Collection\ColumnFactories(
            $factories
        );
    }

}