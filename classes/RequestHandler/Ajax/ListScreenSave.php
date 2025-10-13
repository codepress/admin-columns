<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC;
use AC\Capabilities;
use AC\Collection\ColumnFactories;
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
use AC\Type\ListScreenId;
use AC\Type\ListScreenStatus;
use AC\Type\TableId;
use InvalidArgumentException;
use RuntimeException;

class ListScreenSave implements RequestAjaxHandler
{

    private Storage $storage;

    private Aggregate $column_factory;

    private TableScreenFactory $table_screen_factory;

    private LabelEncoder $label_encoder;

    private AC\Table\TablePreference $table_preference;

    public function __construct(
        Storage $storage,
        Aggregate $column_factory,
        TableScreenFactory $table_screen_factory,
        LabelEncoder $label_encoder,
        AC\Table\TablePreference $table_preference
    ) {
        $this->storage = $storage;
        $this->column_factory = $column_factory;
        $this->table_screen_factory = $table_screen_factory;
        $this->label_encoder = $label_encoder;
        $this->table_preference = $table_preference;
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

        $list_key = new TableId($request->get('list_key', '') ?? '');
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
        $columns = $this->decode_columns($table_screen, $this->decode_configs((array)$data['columns']));
        $status = new ListScreenStatus($data['status'] ?? null);
        $title = $data['title'] ?: $table_screen->get_labels()->get_singular();

        if ($this->storage->exists($id)) {
            $list_screen = new ListScreen(
                $id,
                (string)$title,
                $table_screen,
                $columns,
                (array)$data['settings'],
                $status
            );
        } else {
            $list_screen = new ListScreen(
                $id,
                (string)$table_screen->get_labels()->get_singular(),
                $this->table_screen_factory->create($list_key),
                $columns,
                (array)$data['settings'],
                $status
            );
        }

        try {
            $this->storage->save($list_screen);
        } catch (RuntimeException $e) {
            $response->set_message($e->getMessage())->error();
        }

        if ( ! $this->storage->exists($id)) {
            $response->set_message(__('Column settings could not be saved.', 'codepress-admin-columns'))->error();
        }

        // Update the user preference to show this view as their preferred list screen.
        $this->table_preference->save(
            $list_screen->get_table_screen()->get_id(),
            $list_screen->get_id()
        );

        do_action('ac/columns/stored', $list_screen);

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
            if ($data['label'] ?? null) {
                $data['label'] = $this->label_encoder->encode($data['label']);
            }

            $config = new Config($data);

            if ( ! $config->has('type')) {
                throw new InvalidArgumentException('Missing column type.');
            }

            $configs->add($config);
        }

        return $configs;
    }

    private function get_column_factory(ColumnFactories $factories, string $type): ?AC\Column\ColumnFactory
    {
        foreach ($factories as $factory) {
            if ($factory->get_column_type() === $type) {
                return $factory;
            }
        }

        return null;
    }

    private function decode_columns(TableScreen $table_screen, ConfigCollection $configs): ColumnCollection
    {
        $columns = new ColumnCollection();

        $factories = $this->column_factory->create($table_screen);

        foreach ($configs as $config) {
            $factory = $this->get_column_factory($factories, $config->get('type'));

            if ($factory) {
                $columns->add($factory->create($config));
            }
        }

        return $columns;
    }

}