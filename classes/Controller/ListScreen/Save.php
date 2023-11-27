<?php

namespace AC\Controller\ListScreen;

use AC\Column\LabelEncoder;
use AC\ColumnCollection;
use AC\ColumnFactory;
use AC\ListScreen;
use AC\ListScreenRepository\Storage;
use AC\Request;
use AC\TableScreenFactory;
use AC\Type\ListKey;
use AC\Type\ListScreenId;

class Save
{

    private $storage;

    private $table_screen_factory;

    public function __construct(Storage $storage, TableScreenFactory $table_screen_factory)
    {
        $this->storage = $storage;
        $this->table_screen_factory = $table_screen_factory;
    }

    public function request(Request $request): void
    {
        $data = json_decode($request->get('data'), true);

        if ( ! isset($data['columns'])) {
            wp_send_json_error(['message' => __('You need at least one column', 'codepress-admin-columns')]);
        }

        $list_key = new ListKey($data['list_screen'] ?? '');

        if ( ! $this->table_screen_factory->can_create($list_key)) {
            wp_send_json_error(['message' => __('List screen not found', 'codepress-admin-columns')]);
        }

        $table_screen = $this->table_screen_factory->create($list_key);

        $list_id = $data['list_screen_id'] ?? '';

        $list_id = ListScreenId::is_valid_id($list_id)
            ? new ListScreenId($list_id)
            : ListScreenId::generate();

        $data = (new Sanitize\FormData())->sanitize($data);
        $column_factory = new ColumnFactory($table_screen);

        $list_screen = new ListScreen(
            $list_id,
            $data['title'] ?? '',
            $table_screen,
            $this->get_columns($column_factory, $data['columns'] ?? []),
            $data['settings'] ?? []
        );

        $this->storage->save($list_screen);

        if ( ! $this->storage->exists($list_id)) {
            wp_send_json_error(['message' => __('Column settings could not be saved.', 'codepress-admin-columns')]);
        }

        do_action('ac/columns_stored', $list_screen);

        wp_send_json_success([
            'message' => sprintf(
                '%s %s',
                sprintf(
                    __('Settings for %s updated successfully.', 'codepress-admin-columns'),
                    sprintf('<strong>%s</strong>', esc_html($list_screen->get_title() ?: $list_screen->get_label()))
                ),
                ac_helper()->html->link(
                    (string)$list_screen->get_table_url(),
                    sprintf(__('View %s screen', 'codepress-admin-columns'), $list_screen->get_label())
                )
            ),
            'list_id' => $list_id->get_id(),
        ]);
    }

    private function get_columns(ColumnFactory $column_factory, array $columndata): ColumnCollection
    {
        $columns = [];

        foreach ($columndata as $data) {
            if (isset($data['label'])) {
                $data['label'] = (new LabelEncoder())->encode($data['label']);
            }

            $columns[] = $column_factory->create($data);
        }

        return new ColumnCollection(array_filter($columns));
    }

}