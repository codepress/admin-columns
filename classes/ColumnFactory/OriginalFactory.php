<?php

declare(strict_types=1);

namespace AC\ColumnFactory;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactoryRegistry;

class OriginalFactory extends BaseColumnFactory
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

    public function get_column_type(): string
    {
        return $this->type;
    }

    public function get_label(): string
    {
        return $this->label;
    }

    protected function get_group(): string
    {
        return 'default';
    }

}