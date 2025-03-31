<?php

namespace AC\ColumnFactory;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactory\FieldType;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Type\TableScreenContext;
use AC\Value\Formatter\Meta;

class CustomFieldFactory extends BaseColumnFactory
{

    private ComponentFactory\CustomFieldFactory $custom_field_factory;

    private FieldType $field_type;

    private TableScreenContext $table_screen_context;

    private ComponentFactory\BeforeAfter $before_after;

    private ComponentFactory\Pro\TogglePromotionFactory $pro_promotion_factory;

    public function __construct(
        BaseSettingsBuilder $base_settings_builder,
        ComponentFactory\CustomFieldFactory $custom_field_factory,
        TableScreenContext $table_screen_context,
        FieldType $field_type,
        ComponentFactory\BeforeAfter $before_after,
        ComponentFactory\Pro\TogglePromotionFactory $pro_promotion_factory
    ) {
        parent::__construct(
            $base_settings_builder
        );

        $this->custom_field_factory = $custom_field_factory;
        $this->field_type = $field_type;
        $this->table_screen_context = $table_screen_context;
        $this->before_after = $before_after;
        $this->pro_promotion_factory = $pro_promotion_factory;
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->custom_field_factory->create($this->table_screen_context));
        $factories->add($this->field_type);
        $factories->add($this->before_after);
        $factories->add($this->pro_promotion_factory->create(__('Enable Editing', 'codepress-admin-columns')));
        $factories->add($this->pro_promotion_factory->create(__('Enable Bulk Editing', 'codepress-admin-columns')));
        $factories->add($this->pro_promotion_factory->create(__('Enable Export', 'codepress-admin-columns')));
        $factories->add($this->pro_promotion_factory->create(__('Enable Smart Filtering', 'codepress-admin-columns')));
        $factories->add($this->pro_promotion_factory->create(__('Enable Filtering', 'codepress-admin-columns')));
    }

    public function get_column_type(): string
    {
        return 'column-meta';
    }

    public function get_label(): string
    {
        return __('Custom Field', 'codepress-admin-columns');
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->prepend(new Meta($this->table_screen_context->get_meta_type(), $config->get('field', '')));

        return $formatters;
    }

}