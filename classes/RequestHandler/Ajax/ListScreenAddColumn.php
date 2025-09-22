<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC\Capabilities;
use AC\ColumnTypeRepository;
use AC\Nonce;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Response\Json;
use AC\Response\JsonColumnFactory;
use AC\TableScreenFactory\Aggregate;
use AC\Type\TableId;

class ListScreenAddColumn implements RequestAjaxHandler
{

    private Aggregate $table_screen_factory;

    private JsonColumnFactory $json_response_factory;

    private ColumnTypeRepository $repository;

    public function __construct(
        Aggregate $table_screen_factory,
        ColumnTypeRepository $repository,
        JsonColumnFactory $json_response_factory
    ) {
        $this->table_screen_factory = $table_screen_factory;
        $this->repository = $repository;
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

        $list_key = new TableId((string)$request->get('list_screen'));

        if ( ! $this->table_screen_factory->can_create($list_key)) {
            $response->error();
        }

        $column = $this->repository->find(
            $this->table_screen_factory->create($list_key),
            (string)$request->get('column_type')
        );

        if ( ! $column) {
            $response->error();
        }

        $this->json_response_factory->create_by_column($column)
                                    ->success();
    }

}