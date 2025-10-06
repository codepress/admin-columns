<?php

namespace AC\Request\Middleware;

use AC\Admin\Preference;
use AC\ListScreen;
use AC\ListScreenRepository;
use AC\Middleware;
use AC\Request;
use AC\TableScreen;
use AC\Type\ListScreenId;
use Exception;

class ListScreenAdmin implements Middleware
{

    protected ListScreenRepository $storage;

    protected TableScreen $table_screen;

    protected Preference\ListScreen $preference;

    public function __construct(
        ListScreenRepository $storage,
        TableScreen $table_screen,
        Preference\ListScreen $preference
    ) {
        $this->storage = $storage;
        $this->table_screen = $table_screen;
        $this->preference = $preference;
    }

    protected function get_requested_listscreen(Request $request): ?ListScreen
    {
        $id = $this->get_requested_listscreen_id($request);

        return $id
            ? $this->get_list_screen_by_id($id)
            : null;
    }

    protected function get_requested_listscreen_id(Request $request): ?ListScreenId
    {
        try {
            $id = new ListScreenId((string)$request->get('list_screen_id'));
        } catch (Exception $e) {
            return null;
        }

        return $id;
    }

    protected function get_list_screen_by_id(ListScreenId $id): ?ListScreen
    {
        $list_screen = $this->storage->find($id);

        return $list_screen && $this->table_screen->get_id()->equals($list_screen->get_table_id())
            ? $list_screen
            : null;
    }

    private function get_first_listscreen(): ?ListScreen
    {
        return $this->storage->find_all_by_table_id($this->table_screen->get_id())
                             ->first();
    }

    private function get_last_visited_listscreen(): ?ListScreen
    {
        $list_id = $this->preference->get_list_id(
            $this->table_screen->get_id()
        );

        return $list_id
            ? $this->get_list_screen_by_id($list_id)
            : null;
    }

    public function handle(Request $request): void
    {
        $list_screen = $this->get_requested_listscreen($request);

        if ( ! $list_screen) {
            $list_screen = $this->get_last_visited_listscreen();
        }

        if ( ! $list_screen) {
            $list_screen = $this->get_first_listscreen();
        }

        $request->get_parameters()->merge([
            'list_screen' => $list_screen,
        ]);
    }

}