<?php

namespace AC\ColumnFactory;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactory\FieldType;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Type\TableScreenContext;
use AC\Value\Formatter\Meta;

class CustomFieldFactory extends BaseColumnFactory
{

    private $custom_field_factory;

    private $field_type;

    private TableScreenContext $table_screen_context;

    private ComponentFactory\BeforeAfter $before_after;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        ComponentFactory\CustomFieldFactory $custom_field_factory,
        TableScreenContext $table_screen_context,
        FieldType $field_type,
        ComponentFactory\BeforeAfter $before_after
    ) {
        parent::__construct(
            $component_factory_registry
        );

        $this->custom_field_factory = $custom_field_factory;
        $this->field_type = $field_type;
        $this->table_screen_context = $table_screen_context;
        $this->before_after = $before_after;
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->custom_field_factory->create($this->table_screen_context));
        $factories->add($this->field_type);
        $factories->add($this->before_after);
    }

    public function get_column_type(): string
    {
        return 'column-meta';
    }

    public function get_label(): string
    {
        return __('Custom Field', 'codepress-admin-columns');
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->prepend(new Meta($this->table_screen_context->get_meta_type(), $config->get('field', '')));
    }

}