<?php

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC;
use AC\Registerable;
use AC\Table\ManageValue\ListScreenServiceFactory;
use AC\ThirdParty\MediaLibraryAssistant\TableScreen\ManageValueFactory;
use AC\Vendor\DI\Container;

class MediaLibraryAssistant implements Registerable
{

    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function register(): void
    {
        if ( ! defined('MLA_PLUGIN_PATH')) {
            return;
        }

        AC\TableScreenFactory\Aggregate::add($this->container->get(TableScreenFactory::class));
        AC\TableIdsFactory\Aggregate::add($this->container->get(TableIdsFactory::class));
        AC\ColumnFactories\Aggregate::add($this->container->get(ColumnTypesFactory::class));

        AC\Service\ManageValue::add(
            $this->container->make(
                ListScreenServiceFactory::class,
                ['factory' => $this->container->get(ManageValueFactory::class)]
            )
        );
        AC\Service\ManageHeadings::add($this->container->get(TableScreen\ManageHeadings::class));
        AC\Service\SaveHeadings::add($this->container->get(TableScreen\SaveHeadings::class));
    }

}