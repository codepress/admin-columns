<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC\ListScreenRepository\Storage;
use AC\Nonce;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Response\Json;
use AC\Type\ColumnId;
use AC\Type\ListScreenId;
use AC\Value\ExtendedValueRegistry;

class ExtendedValue implements RequestAjaxHandler
{

    private $repository;

    private $views;

    public function __construct(Storage $repository, ExtendedValueRegistry $views)
    {
        $this->repository = $repository;
        $this->views = $views;
    }

    public function handle(): void
    {
        $request = new Request();
        $response = new Json();

        if ( ! (new Nonce\Ajax())->verify($request)) {
            $response->error();
        }

        $id = (int)$request->filter('object_id', null, FILTER_SANITIZE_NUMBER_INT);
        $list_id = (string)$request->filter('list_id', null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $column_name = (string)$request->filter('column_name', null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $view = (string)$request->filter('view', null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $params = (array)$request->get('params', []);

        if ( ! $id) {
            wp_send_json_error(__('Invalid item ID.', 'codepress-admin-columns'), 400);
        }

        if ( ! ListScreenId::is_valid_id($list_id)) {
            wp_send_json_error(__('Invalid list ID.', 'codepress-admin-columns'), 400);
        }

        $list_screen = $this->repository->find(new ListScreenId($list_id));

        if ( ! $list_screen || ! $list_screen->is_user_allowed(wp_get_current_user())) {
            wp_send_json_error(__('Invalid list screen.', 'codepress-admin-columns'), 400);
        }

        $column = $list_screen->get_column(new ColumnId($column_name));

        if ( ! $column) {
            wp_send_json_error(__('Invalid column.', 'codepress-admin-columns'), 400);
        }

        if ( ! $this->views->has_view($view)) {
            wp_send_json_error(__('Invalid view.', 'codepress-admin-columns'), 400);
        }

        header("Cache-Control: max-age=60");

        echo $this->views->get_view($view)->render($id, $params);

        exit;
    }

}