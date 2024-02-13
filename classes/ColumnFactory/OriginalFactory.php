<?php

declare(strict_types=1);

namespace AC\ColumnFactory;

use AC\Column\ColumnFactory;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Settings\Column\LabelFactory;
use AC\Settings\Column\NameFactory;
use AC\Settings\Column\WidthFactory;

class OriginalFactory extends ColumnFactory
{

    private $type;

    private $label;

    public function __construct(
        string $type,
        string $label,
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        NameFactory $name_factory,
        LabelFactory $label_factory,
        WidthFactory $width_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $name_factory, $label_factory, $width_factory);

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