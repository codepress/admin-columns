<?php

declare(strict_types=1);

namespace AC\TableScreen;

use AC\Registerable;
use AC\Request;
use AC\RequestHandler;
use AC\Response;
use AC\TableScreen;

abstract class TableRows implements Registerable, RequestHandler
{

    private ?TableScreen\ListTable $table_screen;

    public function __construct(TableScreen\ListTable $table_screen = null)
    {
        $this->table_screen = $table_screen;
    }

    public function is_request(Request $request): bool
    {
        return $request->get('ac_action') === 'get_table_rows';
    }

    // TODO make abstract method?

    protected function get_rows(array $ids): array
    {
        $rows = [];

        // TODO: do we really need the list_table here? Or can we let the parent class handle this?
        $list_table = $this->table_screen->list_table();

        foreach ($ids as $id) {
            $rows[$id] = $list_table->render_row($id);
        }

        return $rows;
    }

    public function handle(Request $request): void
    {
        check_ajax_referer('ac-ajax');

        $response = new Response\Json();

        $ids = $request->filter('ac_ids', [], FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);

        if ( ! $ids) {
            $response->error();
        }

        $response->set_parameter('table_rows', $this->get_rows($ids));

        $response->success();
    }

}