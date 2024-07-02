<?php

namespace AC;

use AC\ColumnRepository\Sort\ManualOrder;
use AC\Storage\Repository\DefaultColumnsRepository;

class ScreenController implements Registerable
{

    private $headings = [];

    private $table_screen;

    private $default_column_repository;

    private $list_screen;

    public function __construct(
        DefaultColumnsRepository $default_column_repository,
        TableScreen $table_screen,
        ListScreen $list_screen = null
    ) {
        $this->default_column_repository = $default_column_repository;
        $this->table_screen = $table_screen;
        $this->list_screen = $list_screen;
    }

    public function register(): void
    {
        // Headings
        add_filter($this->table_screen->get_heading_hookname(), [$this, 'save_headings'], 199);

        // Headings
        if ($this->list_screen) {
            add_filter($this->table_screen->get_heading_hookname(), [$this, 'add_headings'], 200);

            // Values
            $this->table_screen->manage_value($this->list_screen)->register();
        }
    }

    public function save_headings($headings)
    {
        if ( ! wp_doing_ajax() && $headings) {
            $this->default_column_repository->update($this->table_screen->get_key(), $headings);
        }

        return $headings;
    }

    public function add_headings($headings)
    {
        if (empty($headings)) {
            return [];
        }

        // Run once
        if ($this->headings) {
            return $this->headings;
        }

        $columns = $this->list_screen->get_columns();

        $columns = (new ManualOrder($this->list_screen->get_id()))->sort($columns);

        // Nothing stored. Show default columns on screen.
        if ($columns->count() < 1) {
            return $headings;
        }

        // Add mandatory checkbox
        if (isset($headings['cb'])) {
            $this->headings['cb'] = $headings['cb'];
        }

        foreach ($columns as $column) {
            $setting = $column->get_setting('label');
            $label = $setting ? $setting->get_input()->get_value() : $column->get_label();

            $this->headings[(string)$column->get_id()] = $label;
        }

        return apply_filters('ac/headings', $this->headings, $this->list_screen);
    }

}