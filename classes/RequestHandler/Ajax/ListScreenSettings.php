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
use AC\Type\TableId;
use InvalidArgumentException;

class ListScreenSettings implements RequestAjaxHandler
{

    protected Storage $storage;

    protected AC\TableScreenFactory\Aggregate $table_factory;

    protected Preference\ListScreen $preference;

    protected AC\ColumnTypeRepository $type_repository;

    protected AC\Response\JsonListScreenSettingsFactory $response_factory;

    private AC\Type\ListScreenIdGenerator $list_screen_id_generator;

    public function __construct(
        Storage $storage,
        AC\TableScreenFactory\Aggregate $table_factory,
        AC\ColumnTypeRepository $type_repository,
        Preference\ListScreen $preference,
        AC\Response\JsonListScreenSettingsFactory $response_factory,
        AC\Type\ListScreenIdGenerator $list_screen_id_generator
    ) {
        $this->storage = $storage;
        $this->table_factory = $table_factory;
        $this->preference = $preference;
        $this->type_repository = $type_repository;
        $this->response_factory = $response_factory;
        $this->list_screen_id_generator = $list_screen_id_generator;
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

    protected function set_preference(ListScreen $list_screen): void
    {
        $this->preference->set_last_visited_table($list_screen->get_table_id());
        $this->preference->set_list_id($list_screen->get_table_id(), $list_screen->get_id());
    }

    protected function is_template(ListScreen $list_screen): bool
    {
        return false;
    }

    protected function add_middleware(Request $request, AC\TableScreen $table_screen): void
    {
        $request->add_middleware(
            new Request\Middleware\ListScreenAdmin(
                $this->storage,
                $table_screen,
                $this->preference,
            )
        );
    }

    public function handle(): void
    {
        $this->validate();

        $request = new Request();

        $table_id = new TableId((string)$request->get('list_key'));

        if ( ! $this->table_factory->can_create($table_id)) {
            throw new InvalidArgumentException('Invalid table screen.');
        }

        $table_screen = $this->table_factory->create($table_id);

        $this->add_middleware($request, $table_screen);

        $list_screen = $request->get('list_screen');

        if ($list_screen instanceof ListScreen) {
            $this->set_preference($list_screen);

            $this->response_factory->create($list_screen, true, $this->is_template($list_screen))
                                   ->success();
        }

        $list_screen = new ListScreen(
            $this->list_screen_id_generator->generate(),
            (string)$table_screen->get_labels(),
            $table_screen,
            $this->type_repository->find_all_by_original($table_screen)
        );

        $this->response_factory->create($list_screen, false)
                               ->success();
    }

}