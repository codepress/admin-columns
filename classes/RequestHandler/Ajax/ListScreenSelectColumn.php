<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC;
use AC\Capabilities;
use AC\Column;
use AC\Nonce;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Response\Json;
use AC\Setting\Encoder;
use AC\TableScreenFactory\Aggregate;
use AC\Type\ListKey;

class ListScreenSelectColumn implements RequestAjaxHandler
{

    private $table_screen_factory;

    private $column_factory;

    public function __construct(
        Aggregate $table_screen_factory,
        AC\ColumnFactory $column_factory
    ) {
        $this->table_screen_factory = $table_screen_factory;
        $this->column_factory = $column_factory;
    }

    public function handle(): void
    {
        if ( ! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        $request = new Request();
        $response = new Json();

        if ( ! (new Nonce\Ajax())->verify($request)) {
            $response->error();
        }

        $list_key = new ListKey((string)$request->get('list_key'));

        if ( ! $this->table_screen_factory->can_create($list_key)) {
            $response->error();
        }

        $table_screen = $this->table_screen_factory->create($list_key);

        $column = $this->column_factory->create(
            $table_screen,
            // TODO expects 'type' and for e.g. 'field_type=color'
            $request->get('column_settings') ?: []
        );

        if ( ! $column) {
            $response->error();
        }

        $column_settings = $this->get_column_settings($column);

        $column_config = [
            'settings' => $column_settings,
            'original' => $column->is_original(),
            'id'       => $column->get_name(),
        ];

        $response->set_parameter('columns', $column_config);

        $response->success();

        exit;
    }

    private function get_column_settings(Column $column): array
    {
        return (new Encoder($column->get_settings()))->encode();
    }

}