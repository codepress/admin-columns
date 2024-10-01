<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\Column;
use AC\Registerable;
use AC\Table\ColumnRenderable;
use AC\Type\TaxonomySlug;
use DomainException;

// TODO move to PRO
class Taxonomy implements Registerable
{

    private TaxonomySlug $taxonomy;

    private Column $column;

    public function __construct(TaxonomySlug $taxonomy, Column $column)
    {
        $this->taxonomy = $taxonomy;
        $this->column = $column;
    }

    /**
     * @see WP_Terms_List_Table::column_default
     */
    public function register(): void
    {
        $action = sprintf("manage_%s_custom_column", $this->taxonomy);

        if (did_filter($action)) {
            throw new DomainException("Method should be called before the %s action.", $action);
        }

        add_filter($action, [$this, 'render_value'], 100, 3);
    }

    public function render_value($value, $column_id, $row_id): ?string
    {
        if ((string)$this->column->get_id() !== (string)$column_id) {
            return (string)$value;
        }

        return ColumnRenderable::render($this->column, (int)$row_id);
    }

}