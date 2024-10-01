<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\Registerable;
use AC\Table\Renderable;
use AC\Type\ColumnId;
use DomainException;

class Media implements Registerable
{

    private ColumnId $column_id;

    private Renderable $renderable;

    private int $priority;

    public function __construct(
        ColumnId $column_id,
        Renderable $renderable,
        int $priority = 100
    ) {
        $this->column_id = $column_id;
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
        if ((string)$this->column_id !== (string)$column_id) {
            return;
        }

        echo $this->renderable->render((int)$row_id);
    }

}