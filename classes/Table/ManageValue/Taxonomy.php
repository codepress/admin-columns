<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\ListScreen;
use AC\Table\ManageValue;
use DomainException;

class Taxonomy extends ManageValue
{

    private $taxonomy;

    public function __construct(string $taxonomy, ListScreen $list_screen)
    {
        parent::__construct($list_screen);

        $this->taxonomy = $taxonomy;
    }

    /**
     * @see WP_Terms_List_Table::column_default
     */
    public function register(): void
    {
        $action = sprintf("manage_%s_custom_column", $this->taxonomy);

        if (did_action($action)) {
            throw new DomainException("Method should be called before the %s action.", $action);
        }

        add_action($action, [$this, 'render_value'], 100, 3);
    }

    public function render_value($value, $column_name, $term_id): void
    {
        echo $this->render_cell((string)$column_name, (int)$term_id, (string)$value);
    }

}