<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC\Capabilities;
use AC\ColumnCollection;
use AC\ColumnTypeRepository;
use AC\DefaultColumnHandler;
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

    private DefaultColumnHandler $default_column_handler;

    public function __construct(
        Aggregate $table_screen_factory,
        ColumnTypeRepository $column_type_repository,
        DefaultColumnHandler $default_column_handler
    ) {
        $this->table_screen_factory = $table_screen_factory;
        $this->column_type_repository = $column_type_repository;
        $this->default_column_handler = $default_column_handler;
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

        $default_columns = $this->default_column_handler->handle(
            $table_screen,
            $this->column_type_repository->find_all_original($table_screen)
        );

        // TODO David implement the handler for data sources
        // TODO David use the defaults over the originals?
        // TODO Stefan implement the proper settings to be returned

        $response->set_parameter('columns', $this->get_columns($default_columns));
        $response->set_parameter('config', $this->get_config($default_columns));
        $response->success();
    }

    private function get_config(ColumnCollection $columns): array
    {
        $settings = [];

        foreach ($columns as $column) {
            $settings[$column->get_type()] = (new Encoder($column->get_settings()))->encode();
        }

        return $settings;
    }

    private function get_columns(ColumnCollection $columns): array
    {
        $abstracts = [];

        foreach ($columns as $column) {
            $abstracts[] = [
                'type'  => $column->get_type(),
                'label' => $column->get_label(),
                'name'  => $column->get_type(),
            ];
        }

        return $abstracts;
    }

}