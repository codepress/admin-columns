<?php

namespace AC\Request\Middleware;

use AC\Admin\Preference;
use AC\ListScreen;
use AC\ListScreenRepository\Storage;
use AC\Middleware;
use AC\Request;
use AC\TableScreen;
use AC\Type\ListScreenId;
use AC\Type\TableId;
use Exception;

class ListScreenAdmin implements Middleware
{

    private Storage $storage;

    private TableScreen $table_screen;

    private Preference\ListScreen $preference;

    public function __construct(
        Storage $storage,
        TableScreen $table_screen,
        Preference\ListScreen $preference,
    ) {
        $this->storage = $storage;
        $this->table_screen = $table_screen;
        $this->preference = $preference;
    }

    private function get_requested_list_screen(Request $request): ?ListScreen
    {
        try {
            $id = new ListScreenId((string)$request->get('list_screen_id'));
        } catch (Exception $e) {
            return null;
        }

        return $this->get_list_screen_by_id($id);
    }

    private function get_list_screen_by_id(ListScreenId $id): ?ListScreen
    {
        $list_screen = $this->storage->find($id);

        return $list_screen && $this->table_screen->get_id()->equals($list_screen->get_table_id())
            ? $list_screen
            : null;
    }

    private function get_first_listscreen(): ?ListScreen
    {
        $list_screens = $this->storage->find_all_by_table_id($this->table_screen->get_id());

        return $list_screens->count() > 0
            ? $list_screens->current()
            : null;
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

    private function get_list_screen(Request $request): ?ListScreen
    {
        $list_screen = $this->get_requested_list_screen($request);

        if ( ! $list_screen) {
            $list_screen = $this->get_last_visited_listscreen();
        }

        if ( ! $list_screen) {
            $list_screen = $this->get_first_listscreen();
        }

        return $list_screen;
    }

    public function handle(Request $request): void
    {
        $list_screen = $this->get_list_screen($request);

        if ($list_screen) {
            $this->set_preference($list_screen->get_table_id(), $list_screen->get_id());
        }

        $request->get_parameters()->merge([
            'list_screen' => $list_screen,
        ]);
    }

    private function set_preference(TableId $table_id, ListScreenId $id): void
    {
        $this->preference->set_last_visited_table($table_id);
        $this->preference->set_list_id($table_id, $id);
    }

}