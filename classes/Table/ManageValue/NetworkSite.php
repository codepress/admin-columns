<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\Column;
use AC\Registerable;
use AC\Table\ColumnRenderable;
use DomainException;

// TODO move to PRO
class NetworkSite implements Registerable
{

    private Column $column;

    public function __construct(Column $column)
    {
        $this->column = $column;
    }

    public function register(): void
    {
        if (did_action('manage_sites_custom_column')) {
            throw new DomainException("Method should be called before the action triggers.");
        }

        add_action("manage_sites_custom_column", [$this, 'render_value'], 100, 2);
    }

    public function render_value($column_id, $row_id): void
    {
        if ((string)$this->column->get_id() !== (string)$column_id) {
            return;
        }

        echo ColumnRenderable::render($this->column, (int)$row_id);
    }

}