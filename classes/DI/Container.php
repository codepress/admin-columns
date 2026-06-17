<?php

declare(strict_types=1);

namespace AC\DI;

use AC\Vendor;
use AC\Vendor\DI\DependencyException;
use AC\Vendor\DI\NotFoundException;

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

    /**
     * @template T
     *
     * @param class-string<T>|string $id
     *
     * @return ($id is class-string<T> ? T : mixed)
     *
     * @throws DependencyException|NotFoundException
     */
    public function get(string $id)
    {
        return $this->container->get($id);
    }

    public function has(string $id): bool
    {
        return $this->container->has($id);
    }

    /**
     * @template T
     *
     * @param class-string<T>|string $id
     * @param array                  $parameters
     *
     * @return ($id is class-string<T> ? T : mixed)
     *
     * @throws DependencyException|NotFoundException
     */
    public function make(string $id, array $parameters = [])
    {
        return $this->container->make($id, $parameters);
    }

}
