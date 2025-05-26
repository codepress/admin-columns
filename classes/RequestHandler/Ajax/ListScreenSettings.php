<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC;
use AC\Admin\Preference;
use AC\Capabilities;
use AC\Form\NonceFactory;
use AC\ListScreen;
use AC\ListScreenRepository\Storage;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Type\ListScreenId;
use AC\Type\TableId;
use InvalidArgumentException;

class ListScreenSettings implements RequestAjaxHandler
{

    protected Storage $storage;

    protected AC\TableScreenFactory\Aggregate $table_factory;

    protected Preference\ListScreen $preference;

    protected AC\ColumnTypeRepository $type_repository;

    protected AC\Response\JsonListScreenSettingsFactory $response_factory;

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

    protected function validate(): void
    {
        $response = new AC\Response\Json();

        if ( ! current_user_can(Capabilities::MANAGE)) {
            $response->error();
        }

        if ( ! NonceFactory::create_ajax()->verify(new Request())) {
            $response->error();
        }
    }

    public function handle(): void
    {
        $this->validate();

        $request = new Request();

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