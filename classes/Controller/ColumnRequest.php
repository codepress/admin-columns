<?php

namespace AC\Controller;

use AC;
use AC\Column;
use AC\Column\Placeholder;
use AC\ListScreen;
use AC\Request;
use AC\View;

abstract class ColumnRequest
{

    private $list_screen_factory;

    public function __construct(AC\ListScreenFactory $list_screen_factory)
    {
        $this->list_screen_factory = $list_screen_factory;
    }

    abstract protected function get_column(Request $request, ListScreen $list_screen): ?Column;

    public function request(Request $request): void
    {
        $list_key = (string)$request->get('list_screen');

        if ( ! $this->list_screen_factory->can_create($list_key)) {
            exit;
        }

        $list_screen = $this->list_screen_factory->create($list_key);

        $column = $this->get_column($request, $list_screen);

        if ( ! $column) {
            wp_send_json_error([
                'type'  => 'message',
                'error' => sprintf(
                    __('Please visit the %s screen once to load all available columns', 'codepress-admin-columns'),
                    ac_helper()->html->link((string)$list_screen->get_table_url(), $list_screen->get_label())
                ),
            ]);
        }

        $current_original_columns = (array)json_decode($request->get('current_original_columns', ''), true);

        // Not cloneable message
        if (in_array($column->get_type(), $current_original_columns, true)) {
            wp_send_json_error([
                'type'  => 'message',
                'error' => sprintf(
                    __('%s column is already present and can not be duplicated.', 'codepress-admin-columns'),
                    '<strong>' . $column->get_label() . '</strong>'
                ),
            ]);
        }

        // Placeholder message
        if ($column instanceof Placeholder) {
            wp_send_json_error([
                'type'  => 'message',
                'error' => $column->get_message(),
            ]);
        }

        wp_send_json_success($this->render_column($column));
    }

    private function render_column(Column $column): string
    {
        $view = new View([
            'column' => $column,
        ]);

        $view->set_template('admin/edit-column');

        return $view->render();
    }

}