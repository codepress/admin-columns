<?php

declare(strict_types=1);

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC;

class ManageValue extends AC\Table\ManageValue
{

    private $renderable;

    public function __construct(AC\Table\Renderable\ColumnRenderable $renderable)
    {
        $this->renderable = $renderable;
    }

    public function register(): void
    {
        add_filter('mla_list_table_column_default', [$this, 'render_value'], 100, 3);
    }

    public function render_value($value, $post, $column_name)
    {
        if (is_null($value)) {
            $value = $this->renderable->render($column_name, (int)$post->ID);
        }

        return $value;
    }

}