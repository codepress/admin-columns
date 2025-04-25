<?php

declare(strict_types=1);

namespace AC;

use AC\Asset\Location;
use AC\ListScreenRepository\Storage;
use LogicException;
use Psr\Container\ContainerInterface;

final class Container
{

    private static ?ContainerInterface $instance = null;

    public static function set_container(ContainerInterface $container): void
    {
        if (self::$instance !== null) {
            throw new LogicException('Container is already set.');
        }

        self::$instance = $container;
    }

    public static function get_location(): Location
    {
        return self::$instance->get(AdminColumns::class)->get_location();
    }

    public static function get_storage(): Storage
    {
        return self::$instance->get(Storage::class);
    }

}