<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldTypeConfigurator;

use function AC\Vendor\DI\factory;

use AC\Vendor\Psr\Container\ContainerInterface;

final class FieldTypeConfiguratorProvider
{
    private const CONFIGURATORS = [
        BooleanConfigurator::class,
        ColorConfigurator::class,
        CountConfigurator::class,
        DateConfigurator::class,
        HasContentConfigurator::class,
        HtmlConfigurator::class,
        ImageConfigurator::class,
        MediaConfigurator::class,
        NumericConfigurator::class,
        RelatedPostConfigurator::class,
        RelatedUserConfigurator::class,
        SelectConfigurator::class,
        SerializedConfigurator::class,
        TextConfigurator::class,
        UrlConfigurator::class,
    ];

    public function get_definitions(): array
    {
        return [
            ConfiguratorRegistry::class => factory(function (ContainerInterface $c): ConfiguratorRegistry {
                $registry = new ConfiguratorRegistry();

                foreach (self::CONFIGURATORS as $configurator) {
                    $registry->register($c->get($configurator));
                }

                return $registry;
            }),
        ];
    }

}
