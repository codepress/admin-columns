<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Vendor\DI\Container;

class ComponentCollectionBuilderFactory
{

    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function create(): ComponentCollectionBuilder
    {
        // TODO we use `make` here, because we need the ComponentCollectionBuilder to be non-static
        return $this->container->make(ComponentCollectionBuilder::class);
    }

}