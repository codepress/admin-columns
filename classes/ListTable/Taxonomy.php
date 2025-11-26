<?php

namespace AC\ListTable;

use AC\ListTable;
use WP_Term;
use WP_Terms_List_Table;

class Taxonomy implements ListTable
{

    private WP_Terms_List_Table $table;

    private $taxonomy;

    public function __construct(WP_Terms_List_Table $table, string $taxonomy)
    {
        $this->table = $table;
        $this->taxonomy = $taxonomy;
    }

    public function render_cell(string $column_id, $row_id): string
    {
        return (string)apply_filters("manage_{$this->taxonomy}_custom_column", '', $column_id, $row_id);
    }

    public function render_row($id): string
    {
        $term = get_term_by('id', $id, $this->taxonomy);

        if ( ! $term instanceof WP_Term) {
            return '';
        }

        ob_start();
        $this->table->single_row($term);

        return ob_get_clean();
    }

}