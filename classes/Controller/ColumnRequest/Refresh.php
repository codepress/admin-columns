<?php

namespace AC\Controller\ColumnRequest;

use AC;
use AC\Column;
use AC\Column\Placeholder;
use AC\Request;
use AC\Type\ListKey;
use AC\View;
use Exception;

class Refresh
{

    private $table_screen_factory;

    private $column_types_factory;

    private $column_factory;

    public function __construct(
        AC\TableScreenFactory $table_screen_factory,
        AC\ColumnTypesFactory $column_types_factory,
        AC\ColumnFactory $column_factory
    ) {
        $this->table_screen_factory = $table_screen_factory;
        $this->column_types_factory = $column_types_factory;
        $this->column_factory = $column_factory;
    }

    public function request(Request $request): void
    {
        try {
            $list_key = new ListKey((string)$request->get('list_screen'));
        } catch (Exception $e) {
            exit;
        }

        if ( ! $this->table_screen_factory->can_create($list_key)) {
            exit;
        }

        $table_screen = $this->table_screen_factory->create($list_key);

        $settings = json_decode($request->get('data'), true);
        $settings['name'] = $request->get('column_name');

        $column_types = $this->column_types_factory->create($table_screen);

        $column = $this->column_factory->create($table_screen, $settings);

        if ( ! $column) {
            wp_send_json_error([
                'type'  => 'message',
                'error' => sprintf(
                    __('Please visit the %s screen once to load all available columns', 'codepress-admin-columns'),
                    ac_helper()->html->link((string)$table_screen->get_url(), (string)$table_screen->get_labels())
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

        wp_send_json_success(
            $this->render_column($column, $column_types)
        );
    }

    private function render_column(Column $column, AC\ColumnTypeCollection $column_types): string
    {
        $view = new View([
            'column'       => $column,
            'column_types' => iterator_to_array($column_types),
        ]);

        $view->set_template('admin/edit-column');

        return $view->render();
    }

}