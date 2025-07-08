<?php

declare(strict_types=1);

namespace AC\ThirdParty\MediaLibraryAssistant\TableScreen;

use AC;
use AC\TableScreen\ManageValueService;

class ManageValue implements ManageValueService
{

    private AC\CellRenderer $renderable;

    private int $priority;

    public function __construct(AC\CellRenderer $renderable, int $priority = 100)
    {
        $this->renderable = $renderable;
        $this->priority = $priority;
    }

    public function register(): void
    {
        add_filter('mla_list_table_column_default', [$this, 'render_value'], $this->priority, 3);
    }

    public function render_value(...$args): ?string
    {
        [$value, $post, $column_name] = $args;

        if (is_null($value)) {
            return $this->renderable->render_cell((string)$column_name, $post->ID) ?? $value;
        }

        return $value;
    }

}