<?php

namespace AC\Controller\Middleware;

use AC\Admin\Preference;
use AC\Middleware;
use AC\Request;
use AC\TableScreen;
use AC\TableScreenFactory;
use AC\Type\ListKey;
use Exception;

class TableScreenAdmin implements Middleware
{

    private $preference;

    private $table_screen_factory;

    private $table_screen_fallback;

    public function __construct(
        Preference\ListScreen $preference,
        TableScreenFactory $table_screen_factory,
        TableScreen $table_screen_fallback
    ) {
        $this->preference = $preference;
        $this->table_screen_factory = $table_screen_factory;
        $this->table_screen_fallback = $table_screen_fallback;
    }

    private function get_table_screen_by_key(ListKey $key): ?TableScreen
    {
        return $this->table_screen_factory->can_create($key)
            ? $this->table_screen_factory->create($key)
            : null;
    }

    private function get_requested_table_screen(Request $request): ?TableScreen
    {
        try {
            $key = new ListKey((string)$request->get('list_screen'));
        } catch (Exception $e) {
            return null;
        }

        return $this->get_table_screen_by_key($key);
    }

    private function get_last_visited_table_screen(): ?TableScreen
    {
        $list_key = $this->preference->get_last_visited_list_key();

        return $list_key ?
            $this->get_table_screen_by_key($list_key)
            : null;
    }

    private function get_first_table_screen(): ?TableScreen
    {
        return $this->table_screen_fallback;
    }

    private function get_table_screen(Request $request): ?TableScreen
    {
        $table_screen = $this->get_requested_table_screen($request);

        if ( ! $table_screen) {
            $table_screen = $this->get_last_visited_table_screen();
        }

        if ( ! $table_screen) {
            $table_screen = $this->get_first_table_screen();
        }

        return $table_screen;
    }

    public function handle(Request $request): void
    {
        $request->get_parameters()->merge([
            'table_screen' => $this->get_table_screen($request),
        ]);
    }

}