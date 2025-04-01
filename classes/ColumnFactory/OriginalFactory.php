<?php

declare(strict_types=1);

namespace AC\ColumnFactory;

use AC\Column\ColumnFactory;
use AC\Setting\DefaultSettingsBuilder;

class OriginalFactory extends ColumnFactory
{

    private string $type;

    private string $label;

    public function __construct(
        string $type,
        string $label,
        DefaultSettingsBuilder $default_settings_builder
    ) {
        parent::__construct($default_settings_builder);

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