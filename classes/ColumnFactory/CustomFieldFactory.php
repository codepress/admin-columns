<?php

namespace AC\ColumnFactory;

use AC\Column\ColumnFactory;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Settings;
use AC\Settings\Column\LabelFactory;
use AC\Settings\Column\NameFactory;
use AC\Settings\Column\WidthFactory;

class CustomFieldFactory extends ColumnFactory
{

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        NameFactory $name_factory,
        LabelFactory $label_factory,
        WidthFactory $width_factory,
        Settings\Column\CustomFieldFactory $custom_field_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $name_factory, $label_factory, $width_factory);

        $this->register_factory($custom_field_factory);
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