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

    protected function add_required_component_factories(ConditionalComponentFactoryCollection $factories): void
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

        $this->add_required_component_factories($factories);
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

}