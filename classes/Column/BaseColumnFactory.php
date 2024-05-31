<?php

declare(strict_types=1);

namespace AC\Column;

use AC\Column;
use AC\Expression\Specification;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;

abstract class BaseColumnFactory implements ColumnFactory
{

    /**
     * @var ComponentFactoryRegistry
     */
    protected $component_factory_registry;

    /**
     * @var ConditionalComponentFactoryCollection
     */
    protected $component_factories;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry
    ) {
        $this->component_factory_registry = $component_factory_registry;
        $this->component_factories = new ConditionalComponentFactoryCollection();
    }

    protected function add_component_factory(ComponentFactory $factory, Specification $conditions = null): void
    {
        $this->component_factories->add($factory, $conditions);
    }

    protected function add_component_factories(Config $config): void
    {
        $this->add_required_component_factories();
        $this->add_common_component_factories();
    }

    protected function add_required_component_factories(): void
    {
        $this->component_factories
            ->add($this->component_factory_registry->get_name())
            ->add($this->component_factory_registry->get_label());
    }

    protected function add_common_component_factories(): void
    {
        $this->component_factories
            ->add($this->component_factory_registry->get_width());
    }

    protected function create_components(Config $config): ComponentCollection
    {
        $collection = new ComponentCollection();

        foreach ($this->component_factories as $component_factory) {
            $collection->add($component_factory->create($config));
        }

        return $collection;
    }

    protected function get_component_formatters(
        ComponentCollection $components,
        FormatterCollection $formatters
    ): FormatterCollection {
        foreach ($components as $component) {
            foreach ($component->get_formatters() as $formatter) {
                $formatters->add($formatter);
            }
        }

        return $formatters;
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        return $this->get_component_formatters($components, $formatters);
    }

    abstract public function get_column_type(): string;

    abstract protected function get_label(): string;

    protected function get_group(): ?string
    {
        return null;
    }

    public function create(Config $config): Column
    {
        $this->add_component_factories($config);

        $components = $this->create_components($config);

        return new Column\Base(
            $this->get_column_type(),
            $this->get_label(),
            $components,
            $this->get_formatters($components, $config, new FormatterCollection()),
            $this->get_group()
        );
    }

}