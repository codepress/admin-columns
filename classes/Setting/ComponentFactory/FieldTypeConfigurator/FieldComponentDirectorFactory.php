<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldTypeConfigurator;

use AC\Expression\Specification;
use AC\Setting\Component;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactory\FieldTypeFactoryBuilder;
use AC\Setting\Config;
use LogicException;

/**
 * Assembles a Field Type setting from a chosen subset of field types. Immutable: every with_*
 * call returns a copy, so the shared container instance can be configured per call site.
 */
class FieldComponentDirectorFactory implements ComponentFactory
{
    private ConfiguratorRegistry $registry;

    private array $types = [];

    public function __construct(ConfiguratorRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function with(string ...$types): self
    {
        $clone = clone $this;

        foreach ($types as $type) {
            if (! $this->registry->has($type)) {
                throw new LogicException(sprintf('Configurator for field type "%s" not found.', $type));
            }

            if (! in_array($type, $clone->types, true)) {
                $clone->types[] = $type;
            }
        }

        return $clone;
    }

    public function with_basic(): self
    {
        return $this->with(
            TextConfigurator::TYPE,
            NumericConfigurator::TYPE,
            BooleanConfigurator::TYPE,
            ColorConfigurator::TYPE,
            HtmlConfigurator::TYPE,
            ImageConfigurator::TYPE,
            UrlConfigurator::TYPE,
            DateConfigurator::TYPE
        );
    }

    public function with_related(): self
    {
        return $this->with(
            RelatedPostConfigurator::TYPE,
            RelatedUserConfigurator::TYPE,
            MediaConfigurator::TYPE
        );
    }

    public function with_all(): self
    {
        return $this->with(...$this->registry->get_types());
    }

    public function create(Config $config, ?Specification $conditions = null): Component
    {
        $builder = new FieldTypeFactoryBuilder();

        foreach ($this->types as $type) {
            $this->registry->get($type)->configure($builder);
        }

        return $builder->build()->create($config, $conditions);
    }

}
