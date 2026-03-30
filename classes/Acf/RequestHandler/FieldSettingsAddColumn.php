<?php

declare(strict_types=1);

namespace AC\Acf\RequestHandler;

use AC\Acf\AcfColumnFactory;
use AC\Acf\ColumnMatcher;
use AC\Capabilities;
use AC\ColumnCollection;
use AC\ColumnTypeRepository;
use AC\ListScreen;
use AC\ListScreenRepository\Storage;
use AC\Nonce;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Response\Json;
use AC\TableScreenFactory;
use AC\Type\EditorUrlFactory;
use AC\Type\ListScreenIdGenerator;
use AC\Type\TableId;

class FieldSettingsAddColumn implements RequestAjaxHandler
{

    private Storage $storage;

    private TableScreenFactory $table_screen_factory;

    private AcfColumnFactory $acf_column_factory;

    private ColumnTypeRepository $column_type_repository;

    private ListScreenIdGenerator $list_screen_id_generator;

    private ColumnMatcher $column_matcher;

    public function __construct(
        Storage $storage,
        TableScreenFactory $table_screen_factory,
        AcfColumnFactory $acf_column_factory,
        ColumnTypeRepository $column_type_repository,
        ListScreenIdGenerator $list_screen_id_generator,
        ColumnMatcher $column_matcher
    ) {
        $this->storage = $storage;
        $this->table_screen_factory = $table_screen_factory;
        $this->acf_column_factory = $acf_column_factory;
        $this->column_type_repository = $column_type_repository;
        $this->list_screen_id_generator = $list_screen_id_generator;
        $this->column_matcher = $column_matcher;
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

        $table_id = new TableId((string)$request->get('table_id'));

        if ( ! $this->table_screen_factory->can_create($table_id)) {
            $response->error();
        }

        $table_screen = $this->table_screen_factory->create($table_id);
        $field_data = json_decode((string)$request->get('field_data'), true);

        if ( ! is_array($field_data) || empty($field_data['name'])) {
            $response->error();
        }

        $acf_field = function_exists('acf_get_field') ? acf_get_field($field_data['name']) : false;

        if (is_array($acf_field)) {
            $field_data = $acf_field;
        }

        $column = $this->acf_column_factory->create($table_screen, $field_data);

        if ( ! $column) {
            $response->error();
        }

        $list_screen = $this->find_writable_list_screen($table_id);

        if ($list_screen) {
            $existing_column = $this->column_matcher->find_column($list_screen, $field_data['name']);

            if ($existing_column) {
                $editor_url = EditorUrlFactory::create($table_id, false, $list_screen->get_id())
                    ->with_arg('open_columns', (string)$existing_column->get_id());

                $response->set_parameter('editor_url', (string)$editor_url);
                $response->success();
            }

            $columns = ColumnCollection::from_iterator($list_screen->get_columns());
            $columns->add($column);

            $list_screen->set_columns($columns);
            $this->storage->save($list_screen);
        } else {
            $columns = $this->column_type_repository->find_all_by_original($table_screen);
            $columns->add($column);

            $list_screen = new ListScreen(
                $this->list_screen_id_generator->generate(),
                $table_screen->get_labels()->get_singular(),
                $table_screen,
                $columns
            );

            $this->storage->save($list_screen);
        }

        $editor_url = EditorUrlFactory::create($table_id, false, $list_screen->get_id())
            ->with_arg('open_columns', (string)$column->get_id());

        $response->set_parameter('editor_url', (string)$editor_url);
        $response->success();
    }

    private function find_writable_list_screen(TableId $table_id): ?ListScreen
    {
        foreach ($this->storage->find_all_by_table_id($table_id) as $list_screen) {
            if ( ! $list_screen->is_read_only()) {
                return $list_screen;
            }
        }

        return null;
    }


}
