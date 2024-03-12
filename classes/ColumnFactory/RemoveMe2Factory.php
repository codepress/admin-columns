<?php

namespace AC\ColumnFactory;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Formatter\AggregateBuilderFactory;

class RemoveMe2Factory extends ColumnFactory
{

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry,
        ComponentFactory\DateFormat\Date $date_format,
        ComponentFactory\UserProperty $user_display
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        $this->add_component_factory($date_format);
        $this->add_component_factory($user_display);
    }

    public function get_type(): string
    {
        return 'column-removeme2';
    }

    protected function get_label(): string
    {
        return __('Remove me 2', 'codepress-admin-columns');
    }

}