<?php

declare(strict_types=1);

namespace AC\DI;

use AC\Vendor;

/**
 * Container wrapper that abstracts the underlying dependency injection container
 */
final class Container implements Vendor\Psr\Container\ContainerInterface, FactoryContainer
{

    private Vendor\DI\Container $container;

    public function __construct(Vendor\DI\Container $container)
    {
        $this->container = $container;
    }

    public function get(string $id)
    {
        return $this->container->get($id);
    }

    public function has(string $id): bool
    {
        return $this->container->has($id);
    }

    public function make(string $id, array $parameters = [])
    {
        return $this->container->make($id, $parameters);
    }

}