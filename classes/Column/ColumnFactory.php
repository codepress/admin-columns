<?php

declare(strict_types=1);

namespace AC\Column;

use AC\Column;
use AC\Expression\Specification;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Settings\Column\WidthFactory;
use AC\Settings\SettingFactory;

abstract class ColumnFactory
{

    protected $aggregate_formatter_builder_factory;

    protected $component_factory_registry;

    private $component_factories = [];

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry
    ) {
        $this->aggregate_formatter_builder_factory = $aggregate_formatter_builder_factory;
        $this->component_factory_registry = $component_factory_registry;
    }

    protected function register_component_factory(SettingFactory $factory, Specification $specification = null): void
    {
        $this->component_factories[] = [
            $factory,
            $specification,
        ];
    }

    protected function register_component_factories(): void
    {
        $this->register_component_factory($this->component_factory_registry->get_width_factory());

        foreach (get_object_vars($this) as $property) {
            if ($property instanceof SettingFactory && ! $property instanceof WidthFactory) {
                $this->register_component_factory($property);
            }
        }
    }

    protected function create_components(Config $config): ComponentCollection
    {
        $collection = new ComponentCollection();

        foreach ($this->component_factories as $component_factory) {
            $collection->add($component_factory[0]->create($config, $component_factory[1]));
        }

        return $collection;
    }

    protected function create_formatter_builder(ComponentCollection $components): Formatter\AggregateBuilder
    {
        $builder = $this->aggregate_formatter_builder_factory->create();

        foreach ($components as $component) {
            if ($component instanceof Formatter) {
                $builder->add($component);
            }
        }

        return $builder;
    }

    protected function create_column(ComponentCollection $components, Formatter $formatter): Column
    {
        return new Column(
            $this->get_type(),
            $this->get_label(),
            $formatter,
            $components,
            $this->get_group()
        );
    }

    abstract public function get_type(): string;

    abstract protected function get_label(): string;

    protected function get_group(): ?string
    {
        return null;
    }

    public function create(Config $config): Column
    {
        $this->register_component_factories();

        $components = $this->create_components($config);
        $formatter_builder = $this->create_formatter_builder($components);

        return $this->create_column(
            $components,
            $formatter_builder->build()
        );
    }

}