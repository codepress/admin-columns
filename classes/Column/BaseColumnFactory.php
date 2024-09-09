<?php

declare(strict_types=1);

namespace AC\Column;

use AC\Column;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;

abstract class BaseColumnFactory implements ColumnFactory
{

    use BaseComponentFactoryTrait;

    protected $component_factory_registry;

    public function __construct(ComponentFactoryRegistry $component_factory_registry)
    {
        $this->component_factory_registry = $component_factory_registry;
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories
            ->add($this->component_factory_registry->get_width())
            ->add($this->component_factory_registry->get_name())
            ->add($this->component_factory_registry->get_label());
    }

    public function create(Config $config): Column
    {
        $components = new ComponentCollection();
        $factories = new ConditionalComponentFactoryCollection();
        $formatters = new FormatterCollection();

        $this->add_component_factories($factories);
        $this->add_components($components, $factories, $config);
        $this->add_component_formatters($formatters, $components);
        $this->add_formatters($formatters, $config);

        return new Column\Base(
            $this->get_column_type(),
            $this->get_label(),
            $components,
            $formatters,
            $this->get_group()
        );
    }

    //    protected function add_component_factory(ComponentFactory $factory, Specification $conditions = null): void
    //    {
    //        $this->component_factories->add($factory, $conditions);
    //    }

    // TODO
    //    protected function add_component_factories(ConditionalComponentFactoryCollection $factories, Config $config): void
    //    {
    //        $this->add_required_component_factories($factories);
    //        $this->add_common_component_factories($factories);
    //    }

    //    protected function add_required_component_factories(ConditionalComponentFactoryCollection $factories): void
    //    {
    //        $factories
    //            ->add($this->component_factory_registry->get_name())
    //            ->add($this->component_factory_registry->get_label());
    //    }

    //    protected function add_common_component_factories(ConditionalComponentFactoryCollection $factories): void
    //    {
    //        $factories
    //            ->add($this->component_factory_registry->get_width());
    //    }

    //    protected function create_components(
    //        ConditionalComponentFactoryCollection $factories,
    //        Config $config
    //    ): ComponentCollection {
    //        $collection = new ComponentCollection();
    //
    //        foreach ($factories as $component_factory) {
    //            $collection->add($component_factory->create($config));
    //        }
    //
    //        return $collection;
    //    }

    //    protected function get_component_formatters(
    //        ComponentCollection $components,
    //        FormatterCollection $formatters
    //    ): FormatterCollection {
    //        foreach ($components as $component) {
    //            foreach ($component->get_formatters() as $formatter) {
    //                $formatters->add($formatter);
    //            }
    //        }
    //
    //        return $formatters;
    //    }

    // TODO change return to void because the FormatterCollection is already a reference
    //    protected function get_formatters(
    //        ComponentCollection $components,
    //        Config $config,
    //        FormatterCollection $formatters
    //    ): FormatterCollection {
    //        return $this->get_component_formatters($components, $formatters);
    //    }

}