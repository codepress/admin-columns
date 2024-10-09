<?php

declare(strict_types=1);

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC;
use AC\TableScreen\ManageValue\GridRenderable;

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

    public function render_value($value, $post, $column_name)
    {
        if (is_null($value)) {
            $value = $this->renderable->render($column_name, $post->ID);
        }

        return $value;
    }

}