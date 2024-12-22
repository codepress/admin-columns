<?php

declare(strict_types=1);

namespace AC;

use AC\Admin\MenuGroupFactory;
use AC\Admin\MenuGroupFactory\DefaultGroups;
use AC\Admin\PageFactory;
use AC\Admin\PageRequestHandler;
use AC\Admin\PageRequestHandlers;
use AC\Entity\Plugin;
use AC\ListScreenRepository\Storage;
use AC\Plugin\SetupFactory;
use AC\RequestHandler\Ajax;
use AC\Table\ManageHeading;
use AC\Table\ManageValue\ListScreenServiceFactory;
use AC\Table\SaveHeading;
use AC\Value\Extended\MediaPreview;
use AC\Value\Extended\Posts;
use AC\Value\ExtendedValueRegistry;
use AC\Vendor\DI;

class _AdminColumns
{

    public function __construct()
    {
        $container = $this->create_container();

        Container::set_container($container);

        $this->define_factories($container);
        $this->create_services($container)
             ->register();
    }



    public function get_storage(): Storage
    {
        _deprecated_function(__METHOD__, '4.6.5', 'AC\Container::get_storage()');

        return Container::get_storage();
    }

    public function get_url(): string
    {
        _deprecated_function(__METHOD__, '4.6.5', 'ac_get_url()');

        return trailingslashit(Container::get_location()->get_url());
    }

    /**
     * @deprecated
     */
    public function install(): void
    {
    }

}
