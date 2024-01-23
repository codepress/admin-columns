<?php

namespace AC\Request\Middleware;

use AC\Admin\Preference;
use AC\Middleware;
use AC\Request;
use AC\Table\TableScreenCollection;
use AC\TableScreen;
use AC\Type\ListKey;
use Exception;

class TableScreenAdmin implements Middleware
{

    private $preference;

    private $table_screens;

    public function __construct(
        Preference\ListScreen $preference,
        TableScreenCollection $table_screens
    ) {
        $this->preference = $preference;
        $this->table_screens = $table_screens;
    }

    private function get_table_screen_by_key(ListKey $key): ?TableScreen
    {
        foreach ($this->table_screens as $table_screen) {
            if ($table_screen->get_key()->equals($key)) {
                return $table_screen;
            }
        }

        return null;
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

    private function get_first_visit_table_screen(): ?TableScreen
    {
        $table_screen = $this->get_table_screen_by_key(new ListKey('post'));

        return $table_screen ?: $this->table_screens->offsetGet($this->table_screens->count() - 1);
    }

    private function get_table_screen(Request $request): ?TableScreen
    {
        $table_screen = $this->get_requested_table_screen($request);

        if ( ! $table_screen) {
            $table_screen = $this->get_last_visited_table_screen();
        }

        if ( ! $table_screen) {
            $table_screen = $this->get_first_visit_table_screen();
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