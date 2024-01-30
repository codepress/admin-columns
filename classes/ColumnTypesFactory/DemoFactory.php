<?php

declare(strict_types=1);

namespace AC\ColumnTypesFactory;

use AC\Column;
use AC\PostType;
use AC\Setting\Config;
use AC\Setting\SettingCollection;
use AC\Settings;
use AC\TableScreen;
use AC\Vendor\DI;

// TODO Proof-of-concept POC
class DemoFactory
{

    private $container;

    public function __construct(DI\Container $container)
    {
        $this->container = $container;
    }

    public function create(TableScreen $table_screen, string $type, Config $config): ?Column
    {
        if ( ! $table_screen instanceof PostType) {
            return null;
        }

        switch ($type) {
            case 'column-excerpt':
                return new Column\Post\Excerpt(
                    new SettingCollection([
                        $this->container->get(Settings\Column\LabelFactory::class)->create($config),
                        $this->container->get(Settings\Column\WidthFactory::class)->create($config),
                        $this->container->get(Settings\Column\StringLimitFactory::class)->create($config),
                        $this->container->get(Settings\Column\BeforeAfterFactory::class)->create($config),
                    ])
                );
            case 'column-meta':
                if ( ! $table_screen instanceof TableScreen\MetaType) {
                    return null;
                }

                return new Column\CustomField(
                    $table_screen->get_meta_type(),
                    new SettingCollection([
                        $this->container->get(Settings\Column\CustomFieldFactory::class)->create($config),
                    ])
                );
            default:
                return null;
        }
    }

}