<?php

declare(strict_types=1);

namespace AC\ColumnFactory;

use AC\Column\BaseColumnFactory;
use AC\Setting\BaseSettingsBuilder;

class OriginalFactory extends BaseColumnFactory
{

    private string $type;

    private string $label;

    public function __construct(
        string $type,
        string $label,
        BaseSettingsBuilder $base_settings_builder
    ) {
        parent::__construct($base_settings_builder);

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