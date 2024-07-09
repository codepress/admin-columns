<?php

namespace AC\ColumnFactory;

use AC\Column\BaseColumnFactory;
use AC\MetaType;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\CustomField;
use AC\Setting\ComponentFactory\FieldType;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Meta;

class CustomFieldFactory extends BaseColumnFactory
{

    private $custom_field;

    private $meta_type;

    private $field_type;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        CustomField $custom_field,
        FieldType $field_type,
        MetaType $meta_type
    ) {
        parent::__construct(
            $component_factory_registry
        );

        $this->custom_field = $custom_field;
        $this->meta_type = $meta_type;
        $this->field_type = $field_type;
    }

    protected function add_component_factories(Config $config): void
    {
        parent::add_component_factories($config);

        $this->add_component_factory($this->custom_field);
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
        $formatters->add(new Meta($this->meta_type, $config->get('field', '')));

        return parent::get_formatters($components, $config, $formatters);
    }

}