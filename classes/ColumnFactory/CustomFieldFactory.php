<?php

namespace AC\ColumnFactory;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Settings;

class CustomFieldFactory extends BaseColumnFactory
{

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry,
        Settings\Column\CustomFieldFactory $custom_field_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        $this->add_component_factory($custom_field_factory);
    }

    public function get_type(): string
    {
        return 'column-meta';
    }

    protected function get_label(): string
    {
        return __('Custom Field', 'codepress-admin-columns');
    }

}