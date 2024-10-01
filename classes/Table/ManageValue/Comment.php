<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\Table\ColumnRenderable;
use AC\Table\ManageValue;
use DomainException;

// TODO
class Comment extends ManageValue
{

    private $renderable;

    public function __construct(ColumnRenderable $renderable)
    {
        $this->renderable = $renderable;
    }

    public function register(): void
    {
        if (did_action('manage_comments_custom_column')) {
            throw new DomainException("Method should be called before the action triggers.");
        }

        add_action('manage_comments_custom_column', [$this, 'render_value'], 100, 2);
    }

    public function render_value($column_name, $id): void
    {
        echo $this->renderable->render((string)$column_name, (int)$id);
    }

}