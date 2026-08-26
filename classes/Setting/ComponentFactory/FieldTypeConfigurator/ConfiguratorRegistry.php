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

    public function register(FieldTypeConfigurator $configurator): void
    {
        $this->configurators[$configurator->get_type()] = $configurator;
    }

    /**
     * @return string[]
     */
    public function get_types(): array
    {
        return array_keys($this->configurators);
    }

    public function has(string $type): bool
    {
        return array_key_exists($type, $this->configurators);
    }

    public function get(string $type): FieldTypeConfigurator
    {
        if (! isset($this->configurators[$type])) {
            throw new LogicException(sprintf('Configurator for field type "%s" not found.', $type));
        }

        return $this->configurators[$type];
    }

}
