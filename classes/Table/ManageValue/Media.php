<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\Registerable;
use AC\Table\GridRenderable;
use DomainException;

class Media implements Registerable
{

    private GridRenderable $renderable;

    private int $priority;

    public function __construct(
        GridRenderable $renderable,
        int $priority = 100
    ) {
        $this->renderable = $renderable;
        $this->priority = $priority;
    }

    public function register(): void
    {
        if (did_action('manage_media_custom_column')) {
            throw new DomainException("Method should be called before the action triggers.");
        }

        add_action('manage_media_custom_column', [$this, 'render_value'], $this->priority, 2);
    }

    public function render_value($column_id, $row_id): void
    {
        echo $this->renderable->render($column_id, $row_id);
    }

}