<?php

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC;
use AC\Registerable;
use AC\Vendor\DI\Container;

class MediaLibraryAssistant implements Registerable
{

    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function register(): void
    {
        if ( ! defined('MLA_PLUGIN_PATH')) {
            return;
        }

        AC\TableScreenFactory\Aggregate::add(new TableScreenFactory());
        AC\ListKeysFactory\Aggregate::add(new ListKeysFactory());
        AC\ColumnFactories\Aggregate::add(new ColumnTypesFactory($this->container));

        // TODO test
        AC\Table\ManageValue\AggregateServiceFactory::add($this->container->get(ManageValueFactory::class));
        // TODO ManageHeading and SaveHeading
    }

}