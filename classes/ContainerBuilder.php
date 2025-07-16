<?php

declare(strict_types=1);

namespace AC;

use AC\Exception\ContainerException;
use AC\Vendor\Psr\Container\ContainerInterface;

final class ContainerBuilder
{

    private bool $locked = false;

    private function get_builder(): Vendor\DI\ContainerBuilder
    {
        static $builder = null;

        if ($builder === null) {
            $builder = new Vendor\DI\ContainerBuilder();
        }

        return $builder;
    }

    public function add_definitions(array $definitions): void
    {
        if ($this->locked) {
            ContainerException::from_locked();
        }

        $this->get_builder()->addDefinitions($definitions);
    }

    public function build(): ContainerInterface
    {
        $this->locked = true;

        static $container = null;

        if ($container === null) {
            $container = $this->get_builder()->build();
        }

        return $container;
    }

}