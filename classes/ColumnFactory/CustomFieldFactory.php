<?php

namespace AC\ColumnFactory;

use AC\Column\BaseColumnFactory;
use AC\MetaType;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactory\FieldType;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Type\ListKey;
use AC\Value\Formatter\Meta;

class CustomFieldFactory extends BaseColumnFactory
{

    private $custom_field_factory;

    private $meta_type;

    private $list_key;

    private $field_type;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        ComponentFactory\CustomFieldFactory $custom_field_factory,
        ListKey $list_key,
        FieldType $field_type,
        MetaType $meta_type
    ) {
        parent::__construct(
            $component_factory_registry
        );

        $this->custom_field_factory = $custom_field_factory;
        $this->list_key = $list_key;
        $this->meta_type = $meta_type;
        $this->field_type = $field_type;
    }

    protected function add_component_factories(Config $config): void
    {
        parent::add_component_factories($config);

        $this->add_component_factory($this->custom_field_factory->create($this->list_key));
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