<?php

declare(strict_types=1);

namespace AC\ColumnFactory\CustomField;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Settings\Column\MetaKeyFactory;
use AC\Type\ColumnParent;

class TextFactory extends BaseColumnFactory
{

    public function __construct(
        MetaKeyFactory $meta_key_factory,
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        $this->add_component_factory($meta_key_factory);
    }

    public function get_column_type(): string
    {
        return 'column-meta-text';
    }

    protected function get_label(): string
    {
        return __('Text', 'codepress-admin-columns');
    }

    protected function get_group(): ?string
    {
        return 'custom_field';
    }

    protected function get_parent(): ColumnParent
    {
        return new ColumnParent(
            __('Custom Field', 'codepress-admin-columns'),
            __('Basic', 'codepress-admin-columns')
        );
    }

}