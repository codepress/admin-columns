<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC\Capabilities;
use AC\Column\LabelEncoder;
use AC\ColumnCollection;
use AC\ColumnFactories\Aggregate;
use AC\ListScreen;
use AC\ListScreenRepository\Storage;
use AC\Nonce;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Response\Json;
use AC\Setting\Config;
use AC\Setting\ConfigCollection;
use AC\TableScreen;
use AC\TableScreenFactory;
use AC\Type\ListKey;
use AC\Type\ListScreenId;
use InvalidArgumentException;

class ListScreenSave implements RequestAjaxHandler
{

    private Storage $storage;

    private Aggregate $column_factory;

    private TableScreenFactory $table_screen_factory;

    private LabelEncoder $label_encoder;

    public function __construct(
        Storage $storage,
        Aggregate $column_factory,
        TableScreenFactory $table_screen_factory,
        LabelEncoder $label_encoder
    ) {
        $this->storage = $storage;
        $this->column_factory = $column_factory;
        $this->table_screen_factory = $table_screen_factory;
        $this->label_encoder = $label_encoder;
    }

    public function handle(): void
    {
        if ( ! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        $request = new Request();
        $response = new Json();

        if ( ! (new Nonce\Ajax())->verify($request)) {
            $response->error();
        }

        $list_key = new ListKey($request->get('list_key', '') ?? '');
        $data = $request->get('data', '');
        $data = json_decode($data, true);

        if ( ! $this->table_screen_factory->can_create($list_key)) {
            wp_send_json_error(['message' => __('List screen not found', 'codepress-admin-columns')]);
        }

        $id = $data['id'] ?? null;

        if ( ! ListScreenId::is_valid_id($id)) {
            $response->error();

            exit;
        }

        $id = new ListScreenId($id);
        $table_screen = $this->table_screen_factory->create($list_key);

        if ($this->storage->exists($id)) {
            $list_screen = new ListScreen(
                $id,
                (string)$data['title'],
                $table_screen,
                $this->decode_columns($table_screen, $this->decode_configs((array)$data['columns'])),
                (array)$data['settings']
            );
        } else {
            $list_screen = new ListScreen(
                $id,
                (string)$table_screen->get_labels(),
                $this->table_screen_factory->create($list_key)
            );
        }

        $this->storage->save($list_screen);

        $response
            ->set_message(
                sprintf(
                    '%s %s',
                    sprintf(
                        __('Settings for %s updated successfully.', 'codepress-admin-columns'),
                        sprintf('<strong>%s</strong>', esc_html($list_screen->get_title() ?: $list_screen->get_label()))
                    ),
                    ac_helper()->html->link(
                        (string)$list_screen->get_table_url(),
                        sprintf(__('View %s screen', 'codepress-admin-columns'), $list_screen->get_label())
                    )
                )
            )->success();
    }

    private function decode_configs(array $config_data): ConfigCollection
    {
        $configs = new ConfigCollection();

        foreach ($config_data as $data) {
            $config = new Config($data);

            if ( ! $config->has('type')) {
                throw new InvalidArgumentException('Missing column type.');
            }

            if ($config->has('label')) {
                $config->set('label', $this->label_encoder->encode($config->get('label')));
            }

            $configs->add($config);
        }

        return $configs;
    }

    private function decode_columns(TableScreen $table_screen, ConfigCollection $configs): ColumnCollection
    {
        $columns = new ColumnCollection();

        $factories = iterator_to_array($this->column_factory->create($table_screen));

        foreach ($configs as $config) {
            $factory = $factories[$config->get('type')] ?? null;

            if ($factory) {
                $columns->add($factory->create($config));
            }
        }

        return $columns;
    }

}