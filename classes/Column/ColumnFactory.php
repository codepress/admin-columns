<?php

declare(strict_types=1);

namespace AC\Column;

use AC\Column;
use AC\Expression\Specification;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;

abstract class ColumnFactory
{

    /**
     * @var ComponentFactoryRegistry
     */
    private $component_factory_registry;

    private $component_factories = [];

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry
    ) {
        $this->component_factory_registry = $component_factory_registry;
    }

    protected function add_component_factory(
        ComponentFactory $factory,
        Specification $specification = null,
        int $priority = 10
    ): void {
        $this->component_factories[$priority][] = [
            $factory,
            $specification,
        ];
    }

    protected function add_component_factories(): void
    {
        $this->add_required_component_factories();
        $this->add_common_component_factories();
    }

    protected function add_required_component_factories(): void
    {
        $this->add_component_factory(
            $this->component_factory_registry->get_name(),
            null,
            5
        );
        $this->add_component_factory(
            $this->component_factory_registry->get_label(),
            null,
            5
        );
    }

    protected function add_common_component_factories(): void
    {
        $this->add_component_factory(
            $this->component_factory_registry->get_width(),
            null,
            5
        );
    }

    protected function create_components(Config $config): ComponentCollection
    {
        $collection = new ComponentCollection();

        foreach ($this->component_factories as $component_factory) {
            $collection->add($component_factory[0]->create($config, $component_factory[1]));
        }

        return $collection;
    }

    protected function get_formatters(ComponentCollection $components, $formatters = []): array
    {
        foreach ($components as $component) {
            foreach ($component->get_formatters() as $formatter) {
                $formatters[] = $formatter;
            }
        }

        return $formatters;
    }

    abstract public function get_type(): string;

    abstract protected function get_label(): string;

    protected function get_group(): ?string
    {
        return null;
    }

    public function create(Config $config): Column
    {
        $this->add_component_factories();

        $components = $this->create_components($config);

        return new Column(
            $this->get_type(),
            $this->get_label(),
            $components,
            new FormatterCollection($this->get_formatters($components)),
            $this->get_group()
        );
    }

}