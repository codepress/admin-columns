<?php

declare(strict_types=1);

namespace AC\Column;

use AC\Column;
use AC\Expression\Specification;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Settings\Column\LabelFactory;
use AC\Settings\Column\NameFactory;
use AC\Settings\Column\WidthFactory;
use AC\Settings\SettingFactory;

abstract class ColumnFactory
{

    private $aggregate_formatter_builder_factory;

    private $width_factory;

    private $component_factories = [];

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        NameFactory $name_factory,
        LabelFactory $label_factory,
        WidthFactory $width_factory
    ) {
        $this->aggregate_formatter_builder_factory = $aggregate_formatter_builder_factory;
        $this->width_factory = $width_factory;

        $this->register_factory($name_factory);
        $this->register_factory($label_factory);
    }

    protected function register_factory(SettingFactory $factory, Specification $specification = null): void
    {
        $this->component_factories[] = [
            $factory,
            $specification,
        ];
    }

    protected function register_width_factory(): self
    {
        $this->register_factory($this->width_factory);

        return $this;
    }

    protected function register_factories(): void
    {
        $this->register_width_factory();

        foreach (get_object_vars($this) as $property) {
            if ($property instanceof SettingFactory && ! $property instanceof WidthFactory) {
                $this->register_factory($property);
            }
        }
    }

    protected function get_components(Config $config): ComponentCollection
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

    abstract protected function get_type(): string;

    abstract protected function get_label(): string;

    protected function get_group(): ?string
    {
        return null;
    }

    public function create(Config $config): Column
    {
        $this->register_factories();

        $components = $this->get_components($config);
        $formatter_builder = $this->create_formatter_builder($components);

        return new Column(
            $this->get_type(),
            $this->get_label(),
            $formatter_builder->build(),
            $components,
            $this->get_group()
        );
    }

}