<?php

declare(strict_types=1);

namespace AC\ThirdParty\MediaLibraryAssistant\TableScreen;

use AC;
use AC\TableScreen\ManageValue\GridRenderable;
use AC\Type\ColumnId;

class ManageValue implements AC\Registerable
{

    private GridRenderable $renderable;

    private int $priority;

    public function __construct(GridRenderable $renderable, int $priority = 100)
    {
        $this->renderable = $renderable;
        $this->priority = $priority;
    }

    public function register(): void
    {
        add_filter('mla_list_table_column_default', [$this, 'render_value'], $this->priority, 3);
    }

    public function render_value($value, $post, $column_name): ?string
    {
        if (is_null($value)) {
            return $this->renderable->render(new ColumnId((string)$column_name), $post->ID) ?? $value;
        }

        return $value;
    }

}