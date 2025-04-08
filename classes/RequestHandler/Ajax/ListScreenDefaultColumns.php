<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC\Capabilities;
use AC\ColumnTypeRepository;
use AC\Nonce;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Response;
use AC\Setting\Encoder;
use AC\TableScreen;
use AC\TableScreenFactory\Aggregate;
use AC\Type\TableId;

class ListScreenDefaultColumns implements RequestAjaxHandler
{

    private Aggregate $table_screen_factory;

    private ColumnTypeRepository $column_type_repository;

    public function __construct(
        Aggregate $table_screen_factory,
        ColumnTypeRepository $column_type_repository
    ) {
        $this->table_screen_factory = $table_screen_factory;
        $this->column_type_repository = $column_type_repository;
    }

    public function handle(): void
    {
        if ( ! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        $request = new Request();
        $response = new Response\Json();

        if ( ! (new Nonce\Ajax())->verify($request)) {
            $response->error();
        }

        $list_key = new TableId($request->get('list_key'));

        if ( ! $this->table_screen_factory->can_create($list_key)) {
            $response->error();
        }

        $table_screen = $this->table_screen_factory->create($list_key);

        $response->set_parameter('columns', $this->get_columns($table_screen));
        $response->set_parameter('config', $this->get_config($table_screen));
        $response->success();
    }

    private function get_config(TableScreen $table_screen): array
    {
        $settings = [];

        foreach ($this->column_type_repository->find_all_by_original($table_screen) as $column) {
            $settings[$column->get_type()] = (new Encoder($column->get_settings()))->encode();
        }

        return $settings;
    }

    private function get_columns(TableScreen $table_screen): array
    {
        $columns = [];

        foreach ($this->column_type_repository->find_all_by_original($table_screen) as $column) {
            $columns[] = [
                'type'  => $column->get_type(),
                'label' => $column->get_label(),
                'name'  => $column->get_type(),
            ];
        }

        return $columns;
    }

}