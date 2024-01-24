<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC;
use AC\Capabilities;
use AC\Nonce;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Response;
use AC\Setting\Encoder;
use AC\TableScreen;
use AC\TableScreenFactory\Aggregate;
use AC\Type\ListKey;

class ListScreenDefaultColumns implements RequestAjaxHandler
{

    private $table_screen_factory;

    private $column_types_factory;

    public function __construct(Aggregate $table_screen_factory, AC\ColumnTypesFactory\Aggregate $column_types_factory)
    {
        $this->table_screen_factory = $table_screen_factory;
        $this->column_types_factory = $column_types_factory;
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

        $list_key = new ListKey($request->get('list_key'));

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

        foreach ($this->get_default_column_types($table_screen) as $column) {
            $settings[$column->get_type()] = (new Encoder($column->get_settings()))->encode();
        }

        return $settings;
    }

    private function get_default_column_types(TableScreen $table_screen): array
    {
        $columns = [];

        // TODO Order of default column is wrong
        foreach ($this->column_types_factory->create($table_screen) as $column) {
            if ($column->is_original()) {
                $columns[] = $column;
            }
        }

        return $columns;
    }

    private function get_columns(TableScreen $table_screen): array
    {
        $columns = [];

        foreach ($this->get_default_column_types($table_screen) as $column) {
            $columns[] = [
                'type'  => $column->get_type(),
                'label' => $column->get_label(),
                'name'  => $column->get_type(),
            ];
        }

        return $columns;
    }

}