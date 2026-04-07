<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldTypeConfigurator;

use AC\Vendor\Psr\Container\ContainerInterface;

use function AC\Vendor\DI\factory;

final class FieldTypeConfiguratorProvider
{

    public function get_definitions(): array
    {
        return [
            ConfiguratorRegistry::class => factory(function (ContainerInterface $c): ConfiguratorRegistry {
                $registry = new ConfiguratorRegistry();
                $registry->register('checkmark', $c->get(BooleanConfigurator::class));
                $registry->register('color', $c->get(ColorConfigurator::class));
                $registry->register('text', $c->get(TextConfigurator::class));
                $registry->register('count', $c->get(CountConfigurator::class));
                $registry->register('date', $c->get(DateConfigurator::class));
                $registry->register('has_content', $c->get(HasContentConfigurator::class));
                $registry->register('html', $c->get(HtmlConfigurator::class));
                $registry->register('image', $c->get(ImageConfigurator::class));
                $registry->register('media', $c->get(MediaConfigurator::class));
                $registry->register('numeric', $c->get(NumericConfigurator::class));
                $registry->register('related_post', $c->get(RelatedPostConfigurator::class));
                $registry->register('related_user', $c->get(RelatedUserConfigurator::class));
                $registry->register('select', $c->get(SelectConfigurator::class));
                $registry->register('serialized', $c->get(SerializedConfigurator::class));
                $registry->register('url', $c->get(UrlConfigurator::class));

                return $registry;
            }),
        ];
    }

}