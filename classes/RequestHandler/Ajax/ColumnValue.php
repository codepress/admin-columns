<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC\Column\AjaxValue;
use AC\ListScreenRepository\Storage;
use AC\Nonce;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Response\Json;
use AC\Type\ListScreenId;

class ColumnValue implements RequestAjaxHandler
{

    private $repository;

    public function __construct(Storage $repository)
    {
        $this->repository = $repository;
    }

    public function handle(): void
    {
        $request = new Request();
        $response = new Json();

        if ( ! (new Nonce\Ajax())->verify($request)) {
            $response->error();
        }

        $id = (int)filter_input(INPUT_POST, 'pk');

        if ( ! $id) {
            wp_send_json_error(__('Invalid item ID.', 'codepress-admin-columns'), 400);
        }

        $list_id = filter_input(INPUT_POST, 'layout');

        if ( ! ListScreenId::is_valid_id($list_id)) {
            wp_send_json_error(__('Invalid list ID.', 'codepress-admin-columns'), 400);
        }

        $list_screen = $this->repository->find(new ListScreenId($list_id));

        if ( ! $list_screen || ! $list_screen->is_user_allowed(wp_get_current_user())) {
            wp_send_json_error(__('Invalid list screen.', 'codepress-admin-columns'), 400);
        }

        $column = $list_screen->get_column((string)filter_input(INPUT_POST, 'column'));

        if ( ! $column instanceof AjaxValue) {
            wp_send_json_error(__('Invalid column.', 'codepress-admin-columns'), 400);
        }

        echo $column->get_ajax_value($id);
        exit;
    }

}