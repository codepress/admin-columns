<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC;
use AC\Capabilities;
use AC\Column;
use AC\Nonce;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Response\Json;
use AC\TableScreenFactory\Aggregate;
use AC\Type\ListKey;

class ListScreenAddColumn implements RequestAjaxHandler
{

    private $table_screen_factory;

    private $column_types_factory;

    private $json_response_factory;

    public function __construct(
        Aggregate $table_screen_factory,
        AC\ColumnTypesFactory\Aggregate $column_types_factory,
        AC\Response\JsonColumnFactory $json_response_factory
    ) {
        $this->table_screen_factory = $table_screen_factory;
        $this->column_types_factory = $column_types_factory;
        $this->json_response_factory = $json_response_factory;
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

        $list_key = new ListKey((string)$request->get('list_screen'));

        if ( ! $this->table_screen_factory->can_create($list_key)) {
            $response->error();
        }

        $table_screen = $this->table_screen_factory->create($list_key);

        $column = $this->get_column_type(
            (string)$request->get('column_type'),
            $this->column_types_factory->create($table_screen)
        );

        if ( ! $column) {
            $response->error();
        }

        $this->json_response_factory->create_by_column($column)
                                    ->success();
    }

    private function get_column_type(string $type, AC\ColumnTypeCollection $collection): ?Column
    {
        foreach ($collection as $column) {
            if ($column->get_type() === $type) {
                return $column;
            }
        }

        return null;
    }

}