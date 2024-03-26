<?php

declare(strict_types=1);

namespace AC\ColumnFactory;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentFactoryRegistry;

class OriginalFactory extends ColumnFactory
{

    private $type;

    private $label;

    public function __construct(
        string $type,
        string $label,
        ComponentFactoryRegistry $component_factory_registry
    ) {
        parent::__construct($component_factory_registry);

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