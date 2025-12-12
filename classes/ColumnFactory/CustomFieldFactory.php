<?php

namespace AC\ColumnFactory;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactory\FieldType;
use AC\Setting\Config;
use AC\Setting\DefaultSettingsBuilder;
use AC\Setting\FormatterCollection;
use AC\Type\TableScreenContext;
use AC\Value\Formatter;

class CustomFieldFactory extends BaseColumnFactory
{

    private ComponentFactory\CustomFieldFactory $custom_field_factory;

    private FieldType $field_type;

    private TableScreenContext $table_screen_context;

    private ComponentFactory\BeforeAfter $before_after;

    private ComponentFactory\Pro\TogglePromotionFactory $pro_promotion_factory;

    public function __construct(
        DefaultSettingsBuilder $default_settings_builder,
        ComponentFactory\CustomFieldFactory $custom_field_factory,
        TableScreenContext $table_screen_context,
        FieldType $field_type,
        ComponentFactory\BeforeAfter $before_after,
        ComponentFactory\Pro\TogglePromotionFactory $pro_promotion_factory
    ) {
        parent::__construct(
            $default_settings_builder
        );

        $this->custom_field_factory = $custom_field_factory;
        $this->field_type = $field_type;
        $this->table_screen_context = $table_screen_context;
        $this->before_after = $before_after;
        $this->pro_promotion_factory = $pro_promotion_factory;
    }

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->custom_field_factory->create($this->table_screen_context)->create($config),
            $this->field_type->create($config),
            $this->before_after->create($config),
            $this->pro_promotion_factory->create(__('Enable Editing', 'codepress-admin-columns'))->create($config),
            $this->pro_promotion_factory->create(__('Enable Bulk Editing', 'codepress-admin-columns'))->create($config),
            $this->pro_promotion_factory->create(__('Enable Smart Filtering', 'codepress-admin-columns'))->create(
                $config
            ),
            $this->pro_promotion_factory->create(__('Enable Filtering', 'codepress-admin-columns'))->create($config),
        ]);
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

        if ($config->get('field_type') === FieldType::TYPE_COUNT) {
            return $formatters->prepend(
                Formatter\Aggregate::create([
                    new Formatter\MetaCollection(
                        $this->table_screen_context->get_meta_type(), $config->get('field', '')
                    ),
                    new Formatter\Count(),
                ])
            );
        }

        return $formatters->prepend(
            new Formatter\Meta($this->table_screen_context->get_meta_type(), $config->get('field', ''))
        );
    }

}