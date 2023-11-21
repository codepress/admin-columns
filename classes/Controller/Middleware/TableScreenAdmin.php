<?php

namespace AC\Controller\Middleware;

use AC;
use AC\Admin\Preference;
use AC\Middleware;
use AC\Request;
use AC\TableScreen;
use AC\Type\ListKey;
use Exception;

class TableScreenAdmin implements Middleware
{

    private $preference;

    private $list_keys_factory;

    private $table_screen_factory;

    public function __construct(
        Preference\ListScreen $preference,
        AC\TableScreenFactory $table_screen_factory,
        AC\Table\TableScreensFactoryInterface $list_keys_factory
    ) {
        $this->preference = $preference;
        $this->list_keys_factory = $list_keys_factory;
        $this->table_screen_factory = $table_screen_factory;
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
        try {
            $key = new ListKey((string)$this->preference->get_last_visited_list_key());
        } catch (Exception $e) {
            return null;
        }

        return $this->get_table_screen_by_key($key);
    }

    private function get_first_table_screen(): ?TableScreen
    {
        return $this->get_table_screen_by_key($this->list_keys_factory->create()->current());
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