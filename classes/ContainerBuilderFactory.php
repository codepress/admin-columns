<?php

declare(strict_types=1);

namespace AC;

use AC\Vendor\DI\ContainerBuilder;

final class ContainerBuilderFactory
{

    static private ?ContainerBuilder $builder = null;

    public function create(): ContainerBuilder
    {
        if (self::$builder === null) {
            self::$builder = new ContainerBuilder();
        }

        return self::$builder;
    }

}