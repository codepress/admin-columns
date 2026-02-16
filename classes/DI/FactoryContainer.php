<?php

declare(strict_types=1);

namespace AC\DI;

use AC\Vendor;

interface FactoryContainer
{

    /**
     * @return mixed
     * @throws Vendor\Psr\Container\NotFoundExceptionInterface
     * @throws Vendor\Psr\Container\ContainerExceptionInterface
     */
    public function make(string $id, array $parameters = []);

}