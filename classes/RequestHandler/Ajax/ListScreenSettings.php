<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC;
use AC\Admin\Preference;
use AC\Capabilities;
use AC\ListScreen;
use AC\ListScreenRepository\Storage;
use AC\Nonce;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Type\ListScreenId;
use AC\Type\TableId;
use InvalidArgumentException;

class ListScreenSettings implements RequestAjaxHandler
{

    private Storage $storage;

    private AC\TableScreenFactory\Aggregate $table_factory;

    private Preference\ListScreen $preference;

    private AC\ColumnTypeRepository $type_repository;

    private AC\Response\JsonListScreenSettingsFactory $response_factory;

    public function __construct(
        Storage $storage,
        AC\TableScreenFactory\Aggregate $table_factory,
        AC\ColumnTypeRepository $type_repository,
        Preference\ListScreen $preference,
        AC\Response\JsonListScreenSettingsFactory $response_factory
    ) {
        $this->storage = $storage;
        $this->table_factory = $table_factory;
        $this->preference = $preference;
        $this->type_repository = $type_repository;
        $this->response_factory = $response_factory;
    }

    public function handle(): void
    {
        if ( ! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        $request = new Request();
        $response = new AC\Response\Json();

        if ( ! (new Nonce\Ajax())->verify($request)) {
            $response->error();
        }

        $list_key = new TableId((string)$request->get('list_key'));

        if ( ! $this->table_factory->can_create($list_key)) {
            throw new InvalidArgumentException('Invalid table screen.');
        }

        $table_screen = $this->table_factory->create($list_key);

        $request->add_middleware(
            new Request\Middleware\ListScreenAdmin(
                $this->storage,
                $table_screen,
                $this->preference,
            )
        );

        $list_screen = $request->get('list_screen');

        if ($list_screen instanceof ListScreen) {
            $this->response_factory->create($list_screen)
                                   ->success();
        }

        $list_screen = new ListScreen(
            ListScreenId::generate(),
            (string)$table_screen->get_labels(),
            $table_screen,
            $this->type_repository->find_all_by_original($table_screen)
        );

        $this->response_factory->create($list_screen, false)
                               ->success();
    }

}