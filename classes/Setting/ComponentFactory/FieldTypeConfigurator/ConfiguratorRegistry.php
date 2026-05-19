<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldTypeConfigurator;

use LogicException;

final class ConfiguratorRegistry
{
    /**
     * @var FieldTypeConfigurator[]
     */
    private array $configurators = [];

    public function register(string $key, FieldTypeConfigurator $configurator): void
    {
        $this->configurators[$key] = $configurator;
    }

    public function has(string $key): bool
    {
        return array_key_exists( $key, $this->configurators );
    }

    public function get(string $key): FieldTypeConfigurator
    {
        if ( ! isset($this->configurators[$key])) {
            throw new LogicException(sprintf('Configurator with key "%s" not found.', $key));
        }

        return $this->configurators[$key];
    }

}