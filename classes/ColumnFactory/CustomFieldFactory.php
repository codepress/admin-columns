<?php

namespace AC\ColumnFactory;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactory\FieldType;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Type\TableScreenContext;
use AC\Value\Formatter\Meta;

class CustomFieldFactory extends BaseColumnFactory
{

    private $custom_field_factory;

    private $field_type;

    private $table_screen_context;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        ComponentFactory\CustomFieldFactory $custom_field_factory,
        TableScreenContext $table_screen_context,
        FieldType $field_type
    ) {
        parent::__construct(
            $component_factory_registry
        );

        $this->custom_field_factory = $custom_field_factory;
        $this->field_type = $field_type;
        $this->table_screen_context = $table_screen_context;
    }

    protected function add_component_factories(Config $config): void
    {
        parent::add_component_factories($config);

        $this->add_component_factory($this->custom_field_factory->create($this->table_screen_context));
        $this->add_component_factory($this->field_type);
    }

    public function get_column_type(): string
    {
        return 'column-meta';
    }

    public function get_label(): string
    {
        return __('Custom Field', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Meta($this->table_screen_context->get_meta_type(), $config->get('field', '')));

        return parent::get_formatters($components, $config, $formatters);
    }

}