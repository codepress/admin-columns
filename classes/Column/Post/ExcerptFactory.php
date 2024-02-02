<?php

namespace AC\Column\Post;

use AC\Column;
use AC\Setting\Config;
use AC\Setting\SettingCollection;
use AC\Settings;
use AC\Vendor\DI\Container;

// TODO POC for a column factory
class ExcerptFactory implements Column\ColumnFactory
{

    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function create(Config $config): Column
    {
        return new Excerpt(new SettingCollection([
            $this->container->get(Settings\Column\LabelFactory::class)->create($config),
            $this->container->get(Settings\Column\WidthFactory::class)->create($config),
            $this->container->get(Settings\Column\StringLimitFactory::class)->create($config),
            $this->container->get(Settings\Column\BeforeAfterFactory::class)->create($config),
        ]));
    }
}