<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC;
use AC\Capabilities;
use AC\Nonce;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Response\Json;
use AC\TableScreenFactory\Aggregate;
use AC\Type\ListKey;

class ListScreenSelectColumn implements RequestAjaxHandler
{

    private $table_screen_factory;

    private $column_factory;

    private $json_response_factory;

    public function __construct(
        Aggregate $table_screen_factory,
        AC\ColumnFactory $column_factory,
        AC\Response\JsonColumnFactory $json_response_factory
    ) {
        $this->table_screen_factory = $table_screen_factory;
        $this->column_factory = $column_factory;
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

        $table_screen = $this->table_screen_factory->create(
            new ListKey((string)$request->get('list_key'))
        );

        $column = $this->column_factory->create(
            $table_screen,
            json_decode((string)$request->get('data'), true)
        );

        if ( ! $column) {
            $response->error();
        }

        $this->json_response_factory->create_by_column($column)
                                    ->success();
    }

}