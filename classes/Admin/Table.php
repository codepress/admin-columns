<?php

namespace AC\Admin;

use AC\View;

abstract class Table
{

    protected $message;

    /**
     * @return array [ $column_name => $label, ... ]
     */
    abstract public function get_headings(): array;

    abstract public function get_rows(): array;

    abstract public function render_cell(string $column, $row_data): string;

    public function has_message(): bool
    {
        return null !== $this->message;
    }

    public function get_message(): string
    {
        return $this->message;
    }

    public function render(): string
    {
        $view = new View([
            'table' => $this,
        ]);

        $view->set_template('admin/table');

        return $view->render();
    }

}