<?php

namespace AC\Request\Middleware;

use AC\Admin\Preference;
use AC\ColumnTypeRepository;
use AC\ListScreen;
use AC\ListScreenRepository\Storage;
use AC\Middleware;
use AC\Request;
use AC\TableScreen;
use AC\Type\ListKey;
use AC\Type\ListScreenId;
use Exception;

class ListScreenAdmin implements Middleware
{

    private Storage $storage;

    private TableScreen $table_screen;

    private Preference\ListScreen $preference;

    private ColumnTypeRepository $column_type_repository;

    public function __construct(
        Storage $storage,
        TableScreen $table_screen,
        Preference\ListScreen $preference,
        ColumnTypeRepository $column_type_repository
    ) {
        $this->storage = $storage;
        $this->table_screen = $table_screen;
        $this->preference = $preference;
        $this->column_type_repository = $column_type_repository;
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

        return $list_screen && $this->table_screen->get_key()->equals($list_screen->get_key())
            ? $list_screen
            : null;
    }

    private function get_first_listscreen(): ?ListScreen
    {
        $list_screens = $this->storage->find_all_by_key($this->table_screen->get_key());

        return $list_screens->count() > 0
            ? $list_screens->current()
            : null;
    }

    private function get_last_visited_listscreen(): ?ListScreen
    {
        $list_id = $this->preference->get_list_id(
            $this->table_screen->get_key()
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

        if ( ! $list_screen) {
            $list_screen = new ListScreen(
                ListScreenId::generate(),
                (string)$this->table_screen->get_labels(),
                $this->table_screen,
                $this->column_type_repository->find_all_by_original($this->table_screen)
            );
        }

        return $list_screen;
    }

    public function handle(Request $request): void
    {
        $list_screen = $this->get_list_screen($request);

        if ($list_screen) {
            $this->set_preference($list_screen->get_key(), $list_screen->get_id());
        }

        $request->get_parameters()->merge([
            'list_screen' => $this->get_list_screen($request),
        ]);
    }

    private function set_preference(ListKey $key, ListScreenId $id): void
    {
        $this->preference->set_last_visited_list_key($key);
        $this->preference->set_list_id($key, $id);
    }

}