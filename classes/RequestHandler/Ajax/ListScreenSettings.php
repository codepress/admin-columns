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

    protected Preference\EditorPreference $editor_preference;

    protected AC\Response\JsonListScreenSettingsFactory $response_factory;

    private AC\Type\ListScreenIdGenerator $list_screen_id_generator;

    public function __construct(
        Storage $storage,
        AC\TableScreenFactory\Aggregate $table_factory,
        Preference\EditorPreference $preference,
        AC\Response\JsonListScreenSettingsFactory $response_factory,
        AC\Type\ListScreenIdGenerator $list_screen_id_generator
    ) {
        $this->storage = $storage;
        $this->table_factory = $table_factory;
        $this->editor_preference = $preference;
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

    protected function set_editor_preference(ListScreen $list_screen): void
    {
        $this->editor_preference->save(
            $list_screen->get_table_screen()->get_id(),
            $list_screen->get_id()
        );
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
                $this->editor_preference,
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

        // List Screen and context properties
        $is_template = false;
        $is_stored = false;
        $title = $table_screen->get_labels()->get_singular();

        if ($list_screen instanceof ListScreen) {
            $this->set_editor_preference($list_screen);

            if ( ! trim($list_screen->get_title())) {
                $list_screen->set_title($title);
            }

            $is_template = $this->is_template($list_screen);
            $is_stored = true;
        } else {
            $list_screen = new ListScreen(
                $this->list_screen_id_generator->generate(),
                $title,
                $table_screen
            );
        }

        $this->response_factory->create($list_screen, $is_stored, $is_template)
                               ->success();
    }

}