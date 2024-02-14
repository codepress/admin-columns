<?php

declare(strict_types=1);

namespace AC\ColumnFactory;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Formatter\AggregateBuilderFactory;

class OriginalFactory extends ColumnFactory
{

    private $type;

    private $label;

    public function __construct(
        string $type,
        string $label,
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        $this->type = $type;
        $this->label = $label;
    }

    public function get_type(): string
    {
        return $this->type;
    }

    protected function get_label(): string
    {
        return $this->label;
    }

    protected function get_group(): string
    {
        return 'default';
    }

}