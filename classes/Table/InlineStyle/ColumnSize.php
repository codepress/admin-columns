<?php

declare(strict_types=1);

namespace AC\Table\InlineStyle;

use AC\ColumnSize\ListStorage;
use AC\ColumnSize\UserStorage;
use AC\ListScreen;
use AC\Type\ColumnId;
use AC\Type\ColumnWidth;
use AC\Type\TableId;

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
            if ($list_width) {
                $html .= $this->render_style($table_id, $column_id, $list_width, 'list');
            }

            $user_width = $this->user_storage->get($list_screen->get_id(), $column_id);
            if ($user_width) {
                $html .= $this->render_style($table_id, $column_id, $user_width, 'user');
            }
        }

        return $html;
    }

    private function render_style(TableId $table_id, ColumnId $column_id, ColumnWidth $column_width, string $type): string
    {
        $id = sprintf('ac-column-size-%s-%s', $type, $column_id);
        $rules = $this->build_css_rules($table_id, $column_id, $column_width);

        return sprintf(
            '<style id="%s">@media screen and (min-width: 783px) { %s }</style>',
            esc_attr($id),
            $rules
        );
    }

    private function build_css_rules(TableId $table_id, ColumnId $column_id, ColumnWidth $column_width): string
    {
        $table = esc_attr((string)$table_id);
        $column = esc_attr((string)$column_id);
        $width = $column_width->get_value() . $column_width->get_unit();

        $width_rule = sprintf(
            '.ac-%1$s .wrap table th.column-%2$s, .ac-%1$s .wrap table td.column-%2$s { width: %3$s !important; }',
            $table,
            $column,
            $width
        );

        $overflow_rule = sprintf(
            'body.acp-overflow-table.ac-%1$s .wrap th.column-%2$s, body.acp-overflow-table.ac-%1$s .wrap td.column-%2$s { min-width: %3$s !important; max-width: %3$s !important; }',
            $table,
            $column,
            $width
        );

        return $width_rule . ' ' . $overflow_rule;
    }

}
