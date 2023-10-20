<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC\Capabilities;
use AC\ListScreenFactory;
use AC\ListScreenRepository\Storage;
use AC\Plugin\Version;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Response\Json;
use AC\Settings\Column;
use AC\Type\ListScreenId;
use ACP\Storage\Decoder;
use ACP\Storage\Encoder;

class ListScreenSettings implements RequestAjaxHandler
{

    private $storage;

    /**
     * @var Decoder
     */
    private $decoder;

    /**
     * @var ListScreenFactory
     */
    private $list_screen_factory;

    public function __construct(Storage $storage, ListScreenFactory $list_screen_factory)
    {
        $this->storage = $storage;
        $this->list_screen_factory = $list_screen_factory;
    }

    public function handle(): void
    {
        if ( ! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        $request = new Request();

        switch ($request->get('method', '')) {
            case 'get_settings':
                $this->method_get_settings($request);
            case 'add_column':
                $this->method_add_column($request);
        }

        echo 'EXIT';
        exit;
    }

    private function method_get_settings(Request $request)
    {
        $list_screen = $this->storage->find(new ListScreenId($request->get('list_screen_id')));
        $response = new Json();

        if ( ! $list_screen) {
            $response->error();
        }

        // THIS IS A PRO FEATURE!!! Move?
        $encoder = new Encoder(new Version('6.3'));

        $encoder->set_list_screen($list_screen);

        $settings = [];

        foreach ($list_screen->get_columns() as $column) {
            $settings[$column->get_name()] = $this->get_column_settings($column);
        }

        $response->set_parameter('list_screen_data', $encoder->encode());
        $response->set_parameter('settings', $settings);
        $response->success();
    }

    private function get_column_settings(\AC\Column $column)
    {
        $settings = [];

        foreach ($column->get_settings() as $setting) {
            if ( ! $setting instanceof Column) {
                continue;
            }

            $setting_config = $setting->get_config();

            if ($setting_config) {
                $settings[] = $setting_config;
            }
        }

        return $settings;
    }

    public function method_add_column(Request $request)
    {
        $list_key = (string)$request->get('list_screen');
        $response = new Json();

        if ( ! $this->list_screen_factory->can_create($list_key)) {
            $response->error();
        }

        $list_screen = $this->list_screen_factory->create($list_key);

        if ( ! $list_screen) {
        }

        $column = $list_screen->get_column_by_type($request->get('column_type'));

        if ( ! $column instanceof \AC\Column) {
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

}