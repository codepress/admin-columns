<?php

declare(strict_types=1);

namespace AC\Table\InlineStyle;

use AC\ColumnSize\ListStorage;
use AC\ColumnSize\UserStorage;
use AC\ListScreen;
use AC\Type\ColumnId;
use AC\Type\ColumnWidth;
use AC\Type\TableId;
use AC\View;

class ColumnSize
{

    private ListStorage $list_storage;

    private UserStorage $user_storage;

    public function __construct(ListStorage $list_storage, UserStorage $user_storage)
    {
        $this->list_storage = $list_storage;
        $this->user_storage = $user_storage;
    }

    public function render(ListScreen $list_screen): string
    {
        // auto-width is not supported with wrapping
        if ('auto' === $list_screen->get_preference('wrapping')) {
            return '';
        }

        $html = '';
        $table_id = $list_screen->get_table_id();

        foreach ($list_screen->get_columns() as $column) {
            $column_id = $column->get_id();
            $list_width = $this->list_storage->get($column);
            $user_width = $this->user_storage->get($list_screen->get_id(), $column_id);

            if ( ! $list_width && ! $user_width) {
                continue;
            }

            $html .= $this->render_style($table_id, $column_id, $list_width, $user_width);
        }

        return $html;
    }

    private function render_style(TableId $table_id, ColumnId $column_id, ?ColumnWidth $list_width, ?ColumnWidth $user_width): string
    {
        $view = new View([
            'table_id'   => (string)$table_id,
            'column_id'  => (string)$column_id,
            'list_width' => $this->format_width($list_width),
            'user_width' => $this->format_width($user_width),
        ]);

        return $view->set_template('table/column-size')->render();
    }

    private function format_width(?ColumnWidth $width): ?string
    {
        if ( ! $width) {
            return null;
        }

        return $width->get_value() . $width->get_unit();
    }

}
