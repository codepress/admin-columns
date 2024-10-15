<?php

declare(strict_types=1);

namespace AC\TableScreen;

use AC\ListTable;
use AC\Registerable;
use AC\Request;
use AC\RequestHandler;
use AC\Response;

abstract class TableRows implements Registerable, RequestHandler
{

    private ListTable $list_table;

    public function __construct(ListTable $list_table)
    {
        $this->list_table = $list_table;
    }

    public function is_request(Request $request): bool
    {
        return $request->get('ac_action') === 'get_table_rows';
    }

    public function handle(Request $request): void
    {
        check_ajax_referer('ac-ajax');

        $response = new Response\Json();

        $ids = $request->filter('ac_ids', [], FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);

        if ( ! $ids) {
            $response->error();
        }

        $rows = [];

        foreach ($ids as $id) {
            $rows[$id] = $this->list_table->render_row($id);
        }

        $response->set_parameter('table_rows', $rows);

        $response->success();
    }

}